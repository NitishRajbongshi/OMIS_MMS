<?php

namespace App\Http\Controllers\MMIS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Exception;

class CriticalIndexController extends Controller
{ 
    /**
     * Compute Inspection Grading (0-100, higher = more critical) directly
     * from field observations, using S = 0 / U = 1 as the severity value
     * per activity, weighted by the inspector-assigned weight (1-5).
     *
     * This is the same formula as
     * InspectionController::computeInspectionCriticality() - duplicated
     * here rather than shared, so this controller doesn't depend on
     * InspectionController internals. If the formula ever changes,
     * update both call sites.
     *
     * Inspection Grading = ( Σ(weight_i x grading_value_i) / Σ weight_i ) x 100
     */
    private function computeInspectionGrading($observations): float
    {
        $totalWeight = 0;
        $badnessSum  = 0;

        foreach ($observations as $o) {
            $weight       = (int) ($o->obsrv_weightage ?? 0);
            $gradingValue = strtoupper($o->grading_cd ?? 'S') === 'U' ? 1 : 0;

            $totalWeight += $weight;
            $badnessSum  += $weight * $gradingValue;
        }

        return $totalWeight > 0
            ? round(($badnessSum / $totalWeight) * 100, 2)
            : 0;
    }

    /**
     * Map raw PCI value (0-5 scale) to a display category (5/4/3/<3).
     * Display-only - no longer used to derive criticality (see scalePci()
     * below). Kept so the form/PDF can still show "PCI Category: 3"
     * alongside the numeric raw value.
     */
    private function pciValueToCategory(float $value): string
    {
        return match (true) {
            $value >= 5 => '5',
            $value >= 4 => '4',
            $value >= 3 => '3',
            default     => '<3',
        };
    }

    /*
    |--------------------------------------------------------------------
    | Step 1 - Scaling. All five parameters are direct (no inversions):
    | higher raw value = higher criticality, across the board.
    |--------------------------------------------------------------------
    */

    // PCI: raw 0-5 -> (raw / 5) x 100
    private function scalePci(float $raw): float
    {
        return round((max(0, min(5, $raw)) / 5) * 100, 2);
    }

    // Road Connectivity: raw 1-10 -> (raw / 10) x 100
    private function scaleConnectivity(float $raw): float
    {
        return round((max(0, min(10, $raw)) / 10) * 100, 2);
    }

    // Habitation Score: raw 1-100, already 0-100 - use as-is (clamped)
    private function scaleHabitation(float $raw): float
    {
        return round(max(0, min(100, $raw)), 2);
    }

    // Inspection Grading: already 0-100 from computeInspectionGrading() - use as-is (clamped)
    private function scaleInspectionGrading(float $raw): float
    {
        return round(max(0, min(100, $raw)), 2);
    }

    // Priority Index: raw 1-10 -> (raw / 10) x 100
    private function scalePriorityIndex(float $raw): float
    {
        return round((max(0, min(10, $raw)) / 10) * 100, 2);
    }

    /*
    |--------------------------------------------------------------------
    | Step 2 - Final formula. Weights are now fixed/locked (40/20/10/20/10)
    | later an officer will assigned them.
    |
    | Critical Index = (PCI_scaled x 40 + Connectivity_scaled x 20
    |                  + Habitation_scaled x 10 + InspectionGrading_scaled x 20
    |                  + PriorityIndex_scaled x 10) / 100
    |--------------------------------------------------------------------
    */
    private function computeCriticalIndex(
        float $pciScaled,
        float $connectivityScaled,
        float $habitationScaled,
        float $inspectionGradingScaled,
        float $priorityIndexScaled
    ): array {
        $weightedSum = ($pciScaled * 40)
            + ($connectivityScaled * 20)
            + ($habitationScaled * 10)
            + ($inspectionGradingScaled * 20)
            + ($priorityIndexScaled * 10);

        $index = round($weightedSum / 100, 2);

        $priority = match (true) {
            $index >= 70 => 'High Priority',
            $index >= 40 => 'Medium Priority',
            default      => 'Low Priority',
        };

        return ['index' => $index, 'priority' => $priority];
    }

    /**
     * Map a priority band to the recommended maintenance type.
     * The officer sees this as a default and can override it.
     */
    private function recommendMaintenanceType(string $priority): string
    {
        return match ($priority) {
            'High Priority'   => 'Urgent',
            'Medium Priority' => 'Non-Scheduled Maintenance',
            default           => 'Scheduled Maintenance',
        };
    }

    /**
     * Determine whether this inspection is the latest one recorded for
     * the same asset AND same start/end section - queried directly.
     * Only the latest may proceed to this Critical Index assessment stage
     * (enforced here server-side, not just via the disabled button on the
     * show page).
     */
    private function isLatestForSection(object $inspection): bool
    {
        $latestId = DB::table('maintenance.mtn_inspection_details')
            ->where('insp_asset_id', $inspection->insp_asset_id)
            ->where('start_section', $inspection->start_section)
            ->where('end_section', $inspection->end_section)
            ->orderByDesc('insp_date')
            ->orderByDesc('id')
            ->value('id');

        return $latestId ? ((int) $latestId === (int) $inspection->id) : true;
    }

    /**
     * Fetch one inspection joined with its asset type label, same shape
     * InspectionController's base query produces (kept local here so this
     * controller doesn't depend on InspectionController internals).
     */
    private function getInspection(int $id): ?object
    {
        return DB::table('maintenance.mtn_inspection_details as mid')
            ->leftJoin('public.asset_master_road_sub_assets as sa', 'mid.insp_asset_type', '=', 'sa.sub_asset_cd')
            ->select(['mid.*', 'sa.sub_assets_descr as asset_type'])
            ->where('mid.id', $id)
            ->first();
    }

    /**
     * Show the Critical Index assessment form for a given inspection.
     * GET /mmis/inspection/{id}/critical-index
     *
     * PCI and Inspection Grading are auto-loaded/auto-computed (no manual
     * officer entry, no weight selection - weights are fixed now).
     * Road Connectivity, Habitation Score and Priority Index are still
     * officer-entered raw values on this form, since there's no known
     * source table for them yet.
     */
    public function create(int $id)
    {
        try {

            $inspection = $this->getInspection($id);

            if (!$inspection) {
                return redirect()
                    ->route('mmis.inspection.index')
                    ->with('error', 'Inspection record not found.');
            }

            // Enforce: only the latest inspection for this asset+section
            // may proceed to Critical Index assessment, even via direct URL.
            if (!$this->isLatestForSection($inspection)) {
                return redirect()
                    ->route('mmis.inspection.show', $id)
                    ->with('error', 'Only the latest inspection for this road & section can proceed to Critical Index assessment.');
            }

            $observations = DB::table('maintenance.mtn_insp_observation_details')
                ->where('insp_id', $id)
                ->get();

            // Auto-computed - not officer-entered.
            $inspectionGradingRaw = $this->computeInspectionGrading($observations);

            // Auto-loaded from the PCI table using this inspection's
            // pci_section_cd, instead of letting the officer pick manually.
            $pciValue    = null;
            $pciCategory = null;

            if (!empty($inspection->pci_section_cd)) {
                $pciRecord = DB::table('public.asset_road_pavement_condition_indexes')
                    ->where('pci_section_cd', $inspection->pci_section_cd)
                    ->first();

                if ($pciRecord && isset($pciRecord->pci_value)) {
                    $pciValue    = (float) $pciRecord->pci_value;
                    $pciCategory = $this->pciValueToCategory($pciValue);
                }
            }

            return view('mmis.inspection.criticalIndex', compact(
                'inspection', 'inspectionGradingRaw', 'pciValue', 'pciCategory'
            ));

        } catch (Exception $e) {

            Log::error('CriticalIndexController@create: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()
                ->route('mmis.inspection.index')
                ->with('error', 'Unable to load critical index assessment.');
        }
    }

    /**
     * Save the officer's Critical Index assessment.
     * POST /mmis/inspection/{id}/critical-index
     *
     * Still posts to the external API - no local table for this yet.
     * Give me the schema for where these assessments should be stored
     * and I'll convert this to a DB insert like the rest of the controller.
     *
     * PCI value and Inspection Grading are recomputed/re-fetched server-side
     * from the DB rather than trusted from the submitted form, since they
     * are auto-derived parameters, not officer-entered ones.
     */
    public function store(Request $request, int $id)
    {
        // $validated = $request->validate([
        //     'pci_value'           => 'required|numeric|min:0|max:5',
        //     'connectivity_raw'    => 'required|numeric|min:1|max:10',
        //     'habitation_score'    => 'required|numeric|min:1|max:100',
        //     'priority_index_raw'  => 'required|numeric|min:1|max:10',
        //     'maintenance_type'    => 'required|in:Urgent,Scheduled Maintenance,Non-Scheduled Maintenance',
        //     'override_reason'     => 'nullable|string|max:1000',
        //     'remarks'             => 'nullable|string|max:1000',
        // ]);

        // try {

        //     $user       = Auth::user();
        //     $inspection = $this->getInspection($id);

        //     if (!$inspection) {
        //         return redirect()
        //             ->back()
        //             ->withInput()
        //             ->with('error', 'Inspection record not found.');
        //     }

        //     // Re-derive Inspection Grading from the DB - auto-computed,
        //     // not accepted from client input.
        //     $observations = DB::table('maintenance.mtn_insp_observation_details')
        //         ->where('insp_id', $id)
        //         ->get();

        //     $inspectionGradingRaw = $this->computeInspectionGrading($observations);

        //     // Step 1: scale all five raw values (direct, no inversions).
        //     $pciScaled               = $this->scalePci((float) $validated['pci_value']);
        //     $connectivityScaled      = $this->scaleConnectivity((float) $validated['connectivity_raw']);
        //     $habitationScaled        = $this->scaleHabitation((float) $validated['habitation_score']);
        //     $inspectionGradingScaled = $this->scaleInspectionGrading($inspectionGradingRaw);
        //     $priorityIndexScaled     = $this->scalePriorityIndex((float) $validated['priority_index_raw']);

        //     // Step 2: fixed-weight Critical Index.
        //     $result = $this->computeCriticalIndex(
        //         $pciScaled,
        //         $connectivityScaled,
        //         $habitationScaled,
        //         $inspectionGradingScaled,
        //         $priorityIndexScaled
        //     );

        //     $recommendedType = $this->recommendMaintenanceType($result['priority']);
        //     $finalType       = $validated['maintenance_type'];
        //     $isOverridden    = $finalType !== $recommendedType;

        //     $payload = array_merge($validated, [
        //         'insp_id'                       => $id,
        //         'uid'                           => $user->id,
        //         'pci_scaled'                    => $pciScaled,
        //         'connectivity_scaled'           => $connectivityScaled,
        //         'habitation_scaled'             => $habitationScaled,
        //         'inspection_grading_raw'        => $inspectionGradingRaw,
        //         'inspection_grading_scaled'     => $inspectionGradingScaled,
        //         'priority_index_scaled'         => $priorityIndexScaled,
        //         'critical_index'                => $result['index'],
        //         'priority'                      => $result['priority'],
        //         'recommended_maintenance_type'  => $recommendedType,
        //         'final_maintenance_type'        => $finalType,
        //         'is_overridden'                 => $isOverridden,
        //     ]);

        //     $this->apiPost(config('customconfigpath.SUBMIT_CRITICAL_INDEX'), $payload);

        //     Log::info('CriticalIndexController: critical index saved.', [
        //         'insp_id'     => $id,
        //         'index'       => $result['index'],
        //         'priority'    => $result['priority'],
        //         'recommended' => $recommendedType,
        //         'final'       => $finalType,
        //         'overridden'  => $isOverridden,
        //     ]);

        //     return redirect()
        //         ->route('mmis.inspection.show', $id)
        //         ->with('success', "Critical Index saved: {$result['index']} ({$result['priority']}). Maintenance type: {$finalType}.");

        // } catch (Exception $e) {

        //     Log::error('CriticalIndexController@store: ' . $e->getMessage(), [
        //         'file' => $e->getFile(),
        //         'line' => $e->getLine(),
        //     ]);

        //     return redirect()
        //         ->back()
        //         ->withInput()
        //         ->with('error', 'Unable to save critical index assessment.');
        // }

        dd("this module is not done yet");
    }
}