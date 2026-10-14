<?php

// Save as: app/Http/Controllers/MMIS/InspectionController.php

namespace App\Http\Controllers\MMIS;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InspectionController extends Controller
{
    // The page itself: only the dropdown, the table is filled by AJAX
    public function index(Request $request)
    {
        // Asset type selected in the dropdown when the page opens (default = Road, code 10)
        $selectedType = $request->get('asset_type', '10');

        // Dropdown list: only the asset types that have at least one inspection
        $assetTypes = DB::table('public.asset_master_road_sub_assets as t')
            ->whereIn('t.sub_asset_cd', function ($query) {
                $query->select('insp_asset_type')
                    ->from('maintenance.mtn_inspection_details')
                    ->distinct();
            })
            ->select('t.sub_asset_cd', 't.sub_assets_descr')
            ->orderBy('t.sub_assets_descr')
            ->get();

        return view('mmis.inspection.index', compact('assetTypes', 'selectedType'));
    }

    // AJAX: rows of the main table for the chosen asset type
    public function assetData(Request $request)
    {
        $selectedType = $request->get('asset_type', '10');

        $inspections = DB::table('maintenance.mtn_inspection_details as i')

            // Sub asset name (Road, Bridge, CD Work, Protection Wall...)
            ->leftJoin('public.asset_master_road_sub_assets as a', 'a.sub_asset_cd', '=', 'i.insp_asset_type')

            // Step 1: join the sub asset tables (both sides cast to text)
            ->leftJoin('asset_road_bridge_details as arbd', function ($join) {
                $join->whereRaw('arbd.rd_bridge_cd::text = i.insp_asset_id::text');
            })
            ->leftJoin('asset_road_cdwork_details as arcd', function ($join) {
                $join->whereRaw('arcd.rd_cdwork_cd::text = i.insp_asset_id::text');
            })
            ->leftJoin('asset_protection_wall_details as apwd', function ($join) {
                $join->whereRaw('apwd.protection_wall_cd::text = i.insp_asset_id::text');
            })

            // Step 2: join the road table using the road id of whichever asset matched
            ->leftJoin('asset_road_details as ard', function ($join) {
                $join->whereRaw(
                    'ard.rd_system_id::text = COALESCE(arbd.rd_system_id::text, arcd.rd_system_id::text, apwd.rd_system_id::text, i.insp_asset_id::text)'
                );
            })

            // Only the selected asset type
            ->where('i.insp_asset_type', $selectedType)

            // One row per asset, with only the total number of inspections
            // (the inspection rows themselves are loaded later by AJAX)
            ->select(
                'i.insp_asset_type',
                'i.insp_asset_id',
                'ard.rd_name as insp_asset_location',
                'a.sub_assets_descr',
                DB::raw('COUNT(*) as total_inspections')
            )
            ->groupBy('i.insp_asset_type', 'i.insp_asset_id', 'ard.rd_name', 'a.sub_assets_descr')
            ->orderBy('a.sub_assets_descr')
            ->orderBy('i.insp_asset_id')
            ->get();

        // Prepare each row for the DataTable
        $data = $inspections->map(function ($row) {
            return [
                'asset_type' => $row->sub_assets_descr,
                'asset_id'   => $row->insp_asset_id,
                'asset_name' => $row->insp_asset_location,
                'total'      => $row->total_inspections,
                // address used by the "Inspections (N)" button to load the latest 3
                'latest_url' => route('mmis.inspection.latest', [$row->insp_asset_type, $row->insp_asset_id]),
            ];
        });

        // DataTables reads the "data" key
        return response()->json(['data' => $data]);
    }

    // AJAX: latest 3 inspections of one asset (called when the user clicks the button)
    public function latestInspections($assetType, $assetId)
    {
        $query = DB::table('maintenance.mtn_inspection_details')
            ->where('insp_asset_type', $assetType)
            ->where('insp_asset_id', $assetId);

        // total count (to decide if the "View all" link is needed)
        $total = (clone $query)->count();

        // only the latest 3 rows are read from the database
        $rows = $query
            ->select('id', 'insp_cd', 'insp_date')
            ->orderBy('insp_date', 'desc')
            ->limit(3)
            ->get();

        $items = $rows->map(function ($row) {
            return [
                'insp_cd'  => $row->insp_cd,
                'insp_date' => $row->insp_date ? \Carbon\Carbon::parse($row->insp_date)->format('d-m-Y') : '-',
                'show_url' => route('mmis.inspection.show', $row->id),
                'pdf_url'  => route('mmis.inspection.pdf', $row->id),
            ];
        });

        return response()->json([
            'total'    => $total,
            'items'    => $items,
            'list_url' => route('mmis.inspection.assetList', [$assetType, $assetId]),
        ]);
    }

    // Full list of inspections of ONE asset (opened from "View all inspections")
    public function assetInspections($assetType, $assetId)
    {
        $inspections = DB::table('maintenance.mtn_inspection_details as i')

            // Sub asset name (Road, Bridge, CD Work...)
            ->leftJoin('public.asset_master_road_sub_assets as a', 'a.sub_asset_cd', '=', 'i.insp_asset_type')

            // Road name (same joins as index)
            ->leftJoin('asset_road_bridge_details as arbd', function ($join) {
                $join->whereRaw('arbd.rd_bridge_cd::text = i.insp_asset_id::text');
            })
            ->leftJoin('asset_road_cdwork_details as arcd', function ($join) {
                $join->whereRaw('arcd.rd_cdwork_cd::text = i.insp_asset_id::text');
            })
            ->leftJoin('asset_protection_wall_details as apwd', function ($join) {
                $join->whereRaw('apwd.protection_wall_cd::text = i.insp_asset_id::text');
            })
            ->leftJoin('asset_road_details as ard', function ($join) {
                $join->whereRaw(
                    'ard.rd_system_id::text = COALESCE(arbd.rd_system_id::text, arcd.rd_system_id::text, apwd.rd_system_id::text, i.insp_asset_id::text)'
                );
            })

            // Master tables for names
            // NOTE: the "..._descr" columns are my guess. Change them to your real column names.
            ->leftJoin('maintenance.master_mtn_inspection_types as it', 'it.inspection_type_cd', '=', 'i.insp_type_cd')
            ->leftJoin('maintenance.master_mtn_conditions as c', 'c.condition_type_cd', '=', 'i.cndtn_overall')
            ->leftJoin('maintenance.master_mtn_risk_types as rt', 'rt.risk_type_cd', '=', 'i.risk_type_cd')

            // Only this asset
            ->where('i.insp_asset_type', $assetType)
            ->where('i.insp_asset_id', $assetId)

            ->select(
                'i.id',
                'i.insp_cd',
                'i.insp_date',
                'i.inspector_name',
                'i.inspector_designation',
                'i.start_section',
                'i.end_section',
                'it.inspection_type_descr as insp_type',        // <-- change column name if needed
                'c.condition_type_descr as overall_condition',  // <-- change column name if needed
                'rt.risk_type_descr as risk_type',              // <-- change column name if needed
                'a.sub_assets_descr as asset_type',
                'ard.rd_name as asset_location'
            )
            ->orderBy('i.insp_date', 'desc')
            ->get();

        // Wrong asset type / id in the link
        if ($inspections->isEmpty()) {
            abort(404);
        }

        $first = $inspections->first();

        return view('mmis.inspection.inspectionList', [
            'assetId'        => $assetId,
            'asset_type'     => $first->asset_type,
            'asset_location' => $first->asset_location,
            'inspections'    => $inspections,
        ]);
    }

    // Show full details page
    public function show($id)
    {
        $data = $this->getInspectionData($id);

        return view('mmis.inspection.show', $data);
    }

    // Download the inspection report as PDF
    public function downloadPdf($id)
    {
        $data = $this->getInspectionData($id);

        $pdf = Pdf::loadView('mmis.inspection.inspectionPdf', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->download('Inspection_' . $data['inspection']->insp_cd . '.pdf');
    }

    // Collects all data of one inspection (used by both the show page and the PDF)
    private function getInspectionData($id)
    {
        // 1. Inspection details with the master table names
        //    NOTE: the "..._descr" columns are my guess. Change them to your real column names.
        $inspection = DB::table('maintenance.mtn_inspection_details as i')
            ->leftJoin('maintenance.master_mtn_inspection_types as it', 'it.inspection_type_cd', '=', 'i.insp_type_cd')
            ->leftJoin('public.asset_master_road_sub_assets as a', 'a.sub_asset_cd', '=', 'i.insp_asset_type')
            ->leftJoin('maintenance.master_mtn_conditions as c_overall', 'c_overall.condition_type_cd', '=', 'i.cndtn_overall')
            ->leftJoin('maintenance.master_mtn_conditions as c_pavement', 'c_pavement.condition_type_cd', '=', 'i.cndtn_pavement')
            ->leftJoin('maintenance.master_mtn_conditions as c_drainage', 'c_drainage.condition_type_cd', '=', 'i.cndtn_drainage')
            ->leftJoin('maintenance.master_mtn_conditions as c_shoulder', 'c_shoulder.condition_type_cd', '=', 'i.cndtn_shoulder')
            ->leftJoin('maintenance.master_mtn_conditions as c_structural', 'c_structural.condition_type_cd', '=', 'i.cndtn_structural')
            ->leftJoin('maintenance.master_mtn_conditions as c_safety', 'c_safety.condition_type_cd', '=', 'i.cndtn_safety_features')
            ->leftJoin('maintenance.master_mtn_conditions as c_signage', 'c_signage.condition_type_cd', '=', 'i.cndtn_signage')
            ->leftJoin('maintenance.master_mtn_conditions as c_priority', 'c_priority.condition_type_cd', '=', 'i.recmnd_priority')
            ->leftJoin('maintenance.master_mtn_recm_actions as ra', 'ra.action_type_cd', '=', 'i.recmnd_action_type_cd')
            ->leftJoin('maintenance.master_mtn_risk_types as rt', 'rt.risk_type_cd', '=', 'i.risk_type_cd')

            // Bridge (only when the inspection asset type is Bridge = 1)
            ->leftJoin('asset_road_bridge_details as arbd', function ($join) {
                $join->whereRaw("arbd.rd_bridge_cd::text = i.insp_asset_id::text AND i.insp_asset_type = '1'");
            })

            // Culvert (only when the inspection asset type is Culvert = 0)
            ->leftJoin('asset_road_cdwork_details as arcd', function ($join) {
                $join->whereRaw("arcd.rd_cdwork_cd::text = i.insp_asset_id::text AND i.insp_asset_type = '0'");
            })

            // Road name: for a bridge or culvert take its road, for a road use the asset id itself
            ->leftJoin('asset_road_details as ard', function ($join) {
                $join->whereRaw('ard.rd_system_id::text = COALESCE(arbd.rd_system_id::text, arcd.rd_system_id::text, i.insp_asset_id::text)');
            })

            ->where('i.id', $id)
            ->select(
                'i.*',
                'it.inspection_type_descr as insp_type',          // <-- change column name if needed
                'a.sub_assets_descr as asset_type',
                'ard.rd_name as road_name',
                'c_overall.condition_type_descr as overall_condition',      // <-- change column name if needed
                'c_pavement.condition_type_descr as pavement_condition',
                'c_drainage.condition_type_descr as drainage_condition',
                'c_shoulder.condition_type_descr as shoulder_condition',
                'c_structural.condition_type_descr as structural_condition',
                'c_safety.condition_type_descr as safety_condition',
                'c_signage.condition_type_descr as signage_condition',
                'c_priority.condition_type_descr as priority_name',
                'ra.action_type_descr as action_name',             // <-- change column name if needed
                'rt.risk_type_descr as risk_name'                  // <-- change column name if needed
            )
            ->first();

        // If the inspection id does not exist
        if (!$inspection) {
            abort(404);
        }

        // 2. Observations of this inspection
        $observations = DB::table('maintenance.mtn_insp_observation_details as o')
            ->leftJoin('maintenance.master_mtn_item_activities as act', 'act.activity_cd', '=', 'o.activity_cd')
            ->leftJoin('maintenance.master_mtn_grading_details as g', 'g.grading_cd', '=', 'o.grading_cd')
            ->where('o.insp_id', $id)
            ->select(
                'o.id',
                'o.obsrv_desc',
                'o.obsrv_weightage',
                'act.activity_descr as activity_name',   // <-- change column name if needed
                'g.grading_descr as grading_name'        // <-- change column name if needed
            )
            ->orderBy('o.id')
            ->get();

        // 3. PCI details (only if this inspection has a pci section)
        $pci = null;
        if ($inspection->pci_section_cd) {
            $pci = DB::table('public.asset_road_pavement_condition_indexes')
                ->where('pci_section_cd', $inspection->pci_section_cd)
                ->first();
        }

        // 4. Bridge details (only when the inspection is for a Bridge, asset type = 1)
        $bridge = null;
        if ($inspection->insp_asset_type == '1') {
            $bridge = DB::table('asset_road_bridge_details')
                ->whereRaw('rd_bridge_cd::text = ?', [$inspection->insp_asset_id])
                ->first();

            // To see the real column names of your bridge table, uncomment this line once:
            // dd($bridge);
        }

        // 5. Culvert details (only when the inspection is for a Culvert, asset type = 0)
        $culvert = null;
        if ((string) $inspection->insp_asset_type === '0') {
            $culvert = DB::table('public.asset_road_cdwork_details')
                ->whereRaw('rd_cdwork_cd::text = ?', [$inspection->insp_asset_id])
                ->first();
        }

        return compact('inspection', 'observations', 'pci', 'bridge', 'culvert');
    }
}