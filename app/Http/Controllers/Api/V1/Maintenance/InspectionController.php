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

    public function store(StoreInspectionRequest $request)
    {
        Log::info('StoreInspectionRequest validated data:', $request->validated());

        try {
            $inspection = DB::transaction(function () use ($request) {
                $data = $request->validated();
                $observations = $data['observations'] ?? [];
                unset($data['observations']); // don't pass this through to MtnInspectionDetail::create

                $unique_key = 'INSP-' . strtoupper(uniqid());
                if (empty($data['insp_cd'])) {
                    $data['insp_cd'] = $unique_key;
                }

                $data['created_by'] = auth()->id();
                $data['updated_by'] = auth()->id();

                $inspection = MtnInspectionDetail::create($data);

                if (!empty($observations)) {
                    $now = now();
                    $rows = array_map(function ($obs) use ($inspection, $now) {
                        return [
                            'insp_id'         => $inspection->id,
                            'activity_cd'     => $obs['activity_cd'],
                            'obsrv_desc'      => $obs['obsrv_desc'] ?? null,
                            'grading_cd'      => $obs['grading_cd'] ?? null,
                            'obsrv_weightage' => $obs['obsrv_weightage'] ?? null,
                            'created_by'      => auth()->id(),
                            'updated_by'      => auth()->id(),
                            'created_at'      => $now,
                            'updated_at'      => $now,
                        ];
                    }, $observations);

                    MtnInspObservationDetail::insert($rows);
                }

                return $inspection;
            });

            return response()->json([
                'success' => true,
                'message' => 'Inspection created successfully.',
                'data' => [
                    'id' => $inspection->id,
                    'insp_cd' => $inspection->insp_cd,
                ],
            ], 201);
        } catch (QueryException $e) {
            if ($e->getCode() === '23505') {
                Log::warning('Duplicate insp_cd on inspection create', [
                    'insp_cd' => $request->input('insp_cd'),
                    'user_id' => auth()->id(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'An inspection with this insp_cd already exists.',
                    'error_code' => 'DUPLICATE_INSP_CD',
                ], 409);
            }

            if ($e->getCode() === '23503') {
                Log::warning('FK violation creating inspection/observations', [
                    'message' => $e->getMessage(),
                    'user_id' => auth()->id(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'One of the referenced codes (activity, grading) does not exist.',
                    'error_code' => 'INVALID_REFERENCE',
                ], 422);
            }

            Log::error('Database error creating inspection', [
                'sql_state' => $e->getCode(),
                'message'   => $e->getMessage(),
                'user_id'   => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'A database error occurred while saving the inspection.',
                'error_code' => 'DB_ERROR',
            ], 422);
        } catch (Throwable $e) {
            Log::error('Unexpected error creating inspection', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again.',
                'error_code' => 'SERVER_ERROR',
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
