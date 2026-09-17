<?php

namespace App\Http\Controllers\Api\V1\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\StoreInspectionRequest;
use App\Models\Maintenance\Inspection\MtnInspectionDetail;
use App\Models\Maintenance\Inspection\MtnInspObservationDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InspectionController extends Controller
{
    private function inspectionQuery()
    {
        return DB::table('maintenance.mtn_inspection_details as insp')
            ->leftJoin(
                'maintenance.master_mtn_inspection_types as insp_type',
                'insp_type.inspection_type_cd',
                '=',
                'insp.insp_type_cd'
            )
            ->leftJoin(
                'public.asset_master_road_sub_assets as sub_asset',
                'sub_asset.sub_asset_cd',
                '=',
                'insp.insp_asset_type'
            )
            ->leftJoin(
                'maintenance.master_mtn_conditions as overall_condt',
                'overall_condt.condition_type_cd',
                '=',
                'insp.cndtn_overall'
            )
            ->leftJoin(
                'maintenance.master_mtn_conditions as pavement_condt',
                'pavement_condt.condition_type_cd',
                '=',
                'insp.cndtn_pavement'
            )
            ->leftJoin(
                'maintenance.master_mtn_conditions as drainage_condt',
                'drainage_condt.condition_type_cd',
                '=',
                'insp.cndtn_drainage'
            )
            ->leftJoin(
                'maintenance.master_mtn_conditions as shoulder_condt',
                'shoulder_condt.condition_type_cd',
                '=',
                'insp.cndtn_shoulder'
            )
            ->leftJoin(
                'maintenance.master_mtn_conditions as structural_condt',
                'structural_condt.condition_type_cd',
                '=',
                'insp.cndtn_structural'
            )
            ->leftJoin(
                'maintenance.master_mtn_conditions as safety_condt',
                'safety_condt.condition_type_cd',
                '=',
                'insp.cndtn_safety_features'
            )
            ->leftJoin(
                'maintenance.master_mtn_conditions as signage_condt',
                'signage_condt.condition_type_cd',
                '=',
                'insp.cndtn_signage'
            )
            ->leftJoin(
                'maintenance.master_mtn_risk_types as risk_type',
                'risk_type.risk_type_cd',
                '=',
                'insp.risk_type_cd'
            )
            ->leftJoin(
                'maintenance.master_mtn_recm_actions as recm_action',
                'recm_action.action_type_cd',
                '=',
                'insp.recmnd_action_type_cd'
            )
            ->leftJoin(
                'maintenance.master_mtn_conditions as recm_priority',
                'recm_priority.condition_type_cd',
                '=',
                'insp.recmnd_priority'
            )
            ->select(
                'insp.*',
                'insp_type.inspection_type_descr as insp_type',
                'sub_asset.sub_assets_descr as asset_type',
                'overall_condt.condition_type_descr as overall_condition',
                'pavement_condt.condition_type_descr as pavement_condition',
                'drainage_condt.condition_type_descr as drainage_condition',
                'shoulder_condt.condition_type_descr as shoulder_condition',
                'structural_condt.condition_type_descr as structural_condition',
                'safety_condt.condition_type_descr as safety_condition',
                'signage_condt.condition_type_descr as signage_condition',
                'risk_type.risk_type_descr as risk_type',
                'recm_action.action_type_descr as recm_action_type',
                'recm_priority.condition_type_descr as recm_priority'
            );
    }

    public function index(Request $request)
    {
        try {
            $inspections = $this->inspectionQuery()
                ->orderBy('insp.updated_at')
                ->get();

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => 'Inspection details retrieved successfully.',
                'data' => $inspections,
                'count' => $inspections->count(),
            ], 200);
        } catch (Throwable $e) {

            Log::error('Failed to retrieve all inspection details.', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Unable to retrieve inspection details at this time.',
                'data' => null,
            ], 500);
        }
    }

    private function storePciRecord(array $pciData, int $userId): string
    {
        $now = now();

        $randomNumber = random_int(100, 999);
        $currentTime = time();

        $pciCode = 'PCI_' . $userId . '_' . $currentTime . '_' . $randomNumber;

        $insertData = [
            'pci_section_cd'                => $pciCode,
            'pci_section_length_in_meter'   => $pciData['pci_section_length_in_meter'] ?? null,
            'rd_system_id'                  => $pciData['rd_system_id'] ?? null,
            'chainage'                      => $pciData['chainage'] ?? null,
            'cracking_percent'              => $pciData['cracking_percent'] ?? null,
            'ravelling_percent'             => $pciData['ravelling_percent'] ?? null,
            'pot_holes_percent'             => $pciData['pot_holes_percent'] ?? null,
            'shoving_percent'               => $pciData['shoving_percent'] ?? null,
            'patching_percent'              => $pciData['patching_percent'] ?? null,
            'settlement_depression_percent' => $pciData['settlement_depression_percent'] ?? null,
            'rut_depth'                     => $pciData['rut_depth'] ?? null,
            'tot_motorized_traffic_per_day' => $pciData['tot_motorized_traffic_per_day'] ?? null,
            'tot_comm_veh_traffic_per_day'  => $pciData['tot_comm_veh_traffic_per_day'] ?? null,
            'pv_traffic_light'              => $pciData['pv_traffic_light'] ?? null,
            'pci_value'                     => $pciData['pci_value'],
            'pci_remarks'                   => $pciData['pci_remarks'] ?? null,
            'created_by'                    => $userId,
            'updated_by'                    => $userId,
            'created_at'                    => $now,
            'updated_at'                    => $now,
            'created_at_office_cd'          => $pciData['created_at_office_cd'] ?? null,
            'sent_for_finalize'             => 'N',
            'pci_year'                      => $pciData['pci_year'] ?? null,
            'is_rejected'                   => 'N',
        ];

        Log::info('Attempting to insert PCI record', [
            'pci_section_cd' => $pciCode,
            'pci_value' => $pciData['pci_value'] ?? null,
            'user_id' => $userId,
        ]);

        $inserted = DB::table(
            'public.asset_road_pavement_condition_indexes_draft'
        )->insert($insertData);

        if (!$inserted) {
            Log::error('PCI insert returned false', [
                'pci_section_cd' => $pciCode,
                'user_id' => $userId,
            ]);

            throw new \RuntimeException(
                'Failed to store PCI record.'
            );
        }

        Log::info('PCI record stored successfully', [
            'pci_section_cd' => $pciCode,
        ]);

        return $pciCode;
    }

    public function store(StoreInspectionRequest $request)
    {
        try {

            $inspection = DB::transaction(function () use ($request) {

                $data = $request->validated();

                // Get authenticated user once
                $userId = $data['created_by'];

                Log::info('Inspection API request received', [
                    'user_id' => $userId,
                    'pci_value' => $data['pci_value'] ?? null,
                ]);
                Log::info('Validated inspection data', [
                    'data' => $data,
                ]);

                $observations = $data['observations'] ?? [];

                unset($data['observations']);

                /*
            |--------------------------------------------------------------------------
            | PCI DATA
            |--------------------------------------------------------------------------
            */

                $pciReference = null;

                $hasPciValue =
                    array_key_exists('pci_value', $data)
                    && $data['pci_value'] !== null
                    && $data['pci_value'] !== '';

                if ($hasPciValue) {

                    Log::info('PCI value exists. Creating PCI record.');

                    $pciData = [
                        'pci_section_length_in_meter'   => $data['pci_section_length_in_meter'] ?? null,
                        'rd_system_id'                  => $data['rd_system_id'] ?? null,
                        'chainage'                      => $data['chainage'] ?? null,
                        'cracking_percent'              => $data['cracking_percent'] ?? null,
                        'ravelling_percent'             => $data['ravelling_percent'] ?? null,
                        'pot_holes_percent'             => $data['pot_holes_percent'] ?? null,
                        'shoving_percent'               => $data['shoving_percent'] ?? null,
                        'patching_percent'              => $data['patching_percent'] ?? null,
                        'settlement_depression_percent' => $data['settlement_depression_percent'] ?? null,
                        'rut_depth'                     => $data['rut_depth'] ?? null,
                        'tot_motorized_traffic_per_day' => $data['tot_motorized_traffic_per_day'] ?? null,
                        'tot_comm_veh_traffic_per_day'  => $data['tot_comm_veh_traffic_per_day'] ?? null,
                        'pv_traffic_light'              => $data['pv_traffic_light'] ?? null,
                        'pci_value'                     => $data['pci_value'],
                        'pci_remarks'                   => $data['pci_remarks'] ?? null,
                        'created_at_office_cd'          => $data['created_at_office_cd'] ?? null,
                        'pci_year'                      => $data['pci_year'] ?? null,
                    ];

                    /*
                 * Store PCI first
                 */
                    $pciReference = $this->storePciRecord(
                        $pciData,
                        $userId
                    );

                    Log::info('PCI record created', [
                        'pci_section_cd' => $pciReference,
                    ]);

                    /*
                 * Remove PCI-specific fields from inspection data.
                 */
                    unset(
                        $data['pci_section_length_in_meter'],
                        $data['rd_system_id'],
                        $data['chainage'],
                        $data['cracking_percent'],
                        $data['ravelling_percent'],
                        $data['pot_holes_percent'],
                        $data['shoving_percent'],
                        $data['patching_percent'],
                        $data['settlement_depression_percent'],
                        $data['rut_depth'],
                        $data['tot_motorized_traffic_per_day'],
                        $data['tot_comm_veh_traffic_per_day'],
                        $data['pv_traffic_light'],
                        $data['pci_value'],
                        $data['pci_remarks'],
                        $data['created_at_office_cd'],
                        $data['pci_year']
                    );

                    /*
                 * Store returned PCI reference in inspection.
                 */
                    $data['pci_section_cd'] = $pciReference;
                } else {

                    Log::info('PCI value is NULL. No PCI record will be created.');

                    /*
                 * Remove PCI fields because they don't belong
                 * to mtn_inspection_details.
                 */
                    unset(
                        $data['pci_section_length_in_meter'],
                        $data['rd_system_id'],
                        $data['chainage'],
                        $data['cracking_percent'],
                        $data['ravelling_percent'],
                        $data['pot_holes_percent'],
                        $data['shoving_percent'],
                        $data['patching_percent'],
                        $data['settlement_depression_percent'],
                        $data['rut_depth'],
                        $data['tot_motorized_traffic_per_day'],
                        $data['tot_comm_veh_traffic_per_day'],
                        $data['pv_traffic_light'],
                        $data['pci_value'],
                        $data['pci_remarks'],
                        $data['created_at_office_cd'],
                        $data['pci_year']
                    );

                    /*
                 * Make sure inspection has no PCI reference.
                 */
                    $data['pci_section_cd'] = null;
                }

                /*
            |--------------------------------------------------------------------------
            | INSPECTION
            |--------------------------------------------------------------------------
            */

                if (empty($data['insp_cd'])) {
                    $data['insp_cd'] =
                        'INSP-' . strtoupper(uniqid());
                }

                $data['created_by'] = $userId;
                $data['updated_by'] = $userId;

                Log::info('Creating inspection record', [
                    'insp_cd' => $data['insp_cd'],
                    'pci_section_cd' => $data['pci_section_cd'] ?? null,
                    'user_id' => $userId,
                ]);

                $inspection = MtnInspectionDetail::create($data);

                Log::info('Inspection record created', [
                    'inspection_id' => $inspection->id,
                    'insp_cd' => $inspection->insp_cd,
                ]);

                /*
            |--------------------------------------------------------------------------
            | OBSERVATIONS
            |--------------------------------------------------------------------------
            */

                if (!empty($observations)) {

                    $now = now();

                    $rows = array_map(function ($obs) use (
                        $inspection,
                        $now,
                        $userId
                    ) {

                        return [
                            'insp_id'         => $inspection->id,
                            'activity_cd'     => $obs['activity_cd'],
                            'obsrv_desc'      => $obs['obsrv_desc'] ?? null,
                            'grading_cd'      => $obs['grading_cd'] ?? null,
                            'obsrv_weightage' => $obs['obsrv_weightage'] ?? null,
                            'created_by'      => $userId,
                            'updated_by'      => $userId,
                            'created_at'      => $now,
                            'updated_at'      => $now,
                        ];
                    }, $observations);

                    MtnInspObservationDetail::insert($rows);

                    Log::info('Inspection observations created', [
                        'inspection_id' => $inspection->id,
                        'count' => count($rows),
                    ]);
                }

                return $inspection;
            });

            /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

            return response()->json([
                'success' => true,
                'status_code' => 201,
                'message' => 'Inspection created successfully.',
                'data' => [
                    'id' => $inspection->id,
                    'insp_cd' => $inspection->insp_cd,
                    'pci_section_cd' => $inspection->pci_section_cd,
                ],
            ], 201);
        } catch (QueryException $e) {

            Log::error('Database error creating inspection', [
                'sql_state' => $e->getCode(),
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            if ($e->getCode() === '23505') {

                return response()->json([
                    'success' => false,
                    'status_code' => 409,
                    'message' => 'A duplicate record already exists.',
                    'error_code' => 'DUPLICATE_RECORD',
                    'data' => null,
                ], 409);
            }

            if ($e->getCode() === '23503') {

                return response()->json([
                    'success' => false,
                    'status_code' => 422,
                    'message' => 'One of the referenced records does not exist.',
                    'error_code' => 'INVALID_REFERENCE',
                    'data' => null,
                ], 422);
            }

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'A database error occurred while saving the inspection.',
                'error_code' => 'DB_ERROR',
                'data' => null,
            ], 500);
        } catch (Throwable $e) {

            Log::error('Unexpected error creating inspection', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'An unexpected error occurred while saving the inspection.',
                'error_code' => 'SERVER_ERROR',
                'data' => null,
            ], 500);
        }
    }

    public function show(int $id)
    {
        try {
            // Validate ID before querying the database
            if (!is_numeric($id) || (int) $id <= 0) {
                return response()->json([
                    'success' => false,
                    'status_code' => 422,
                    'message' => 'Invalid inspection ID.',
                    'data' => null,
                ], 422);
            }

            $id = (int) $id;

            $inspection = $this->inspectionQuery()
                ->where('insp.id', $id)
                ->first();

            // Record not found
            if (!$inspection) {
                return response()->json([
                    'success' => false,
                    'status_code' => 404,
                    'message' => 'Inspection details not found.',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => 'Inspection details retrieved successfully.',
                'data' => $inspection,
            ], 200);
        } catch (Throwable $e) {

            Log::error('Failed to retrieve inspection details.', [
                'inspection_id' => $id ?? null,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Unable to retrieve inspection details at this time.',
                'data' => null,
            ], 500);
        }
    }
}
