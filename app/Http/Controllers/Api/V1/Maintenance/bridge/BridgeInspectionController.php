<?php

namespace App\Http\Controllers\Api\V1\Maintenance\bridge;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\Bridge\StoreBridgeInspRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class BridgeInspectionController extends Controller
{
    private function inspectionQuery()
    {
        return DB::table('maintenance.mtn_bridge_insp_details as bridge_insp')
            ->leftJoin(
                'public.asset_road_details as road',
                'road.rd_system_id',
                '=',
                'bridge_insp.rd_system_id'
            )
            ->leftJoin(
                'public.asset_road_bridge_details as bridge',
                'bridge_insp.rd_bridge_cd',
                '=',
                'bridge.rd_bridge_cd'
            )
            ->select(
                'bridge_insp.*',
                'road.rd_name as road_name',
                'bridge.bridge_name',
                'bridge.chainage as bridge_chainage',
                'bridge.bridge_type_cd',
                'bridge.no_of_span',
                'bridge.span_length',
                'bridge.foundation_type_cd',
                'bridge.lowest_water_level',
                'bridge.highest_flood_level'
            );
    }

    public function index()
    {
        try {
            $inspections = $this->inspectionQuery()
                ->orderBy('bridge_insp.updated_at')
                ->get();

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => 'Bridge inspection details retrieved successfully.',
                'data' => $inspections,
                'count' => $inspections->count(),
            ], 200);
        } catch (Throwable $e) {
            Log::error('Failed to retrieve all bridge inspection  details.', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Unable to retrieve bridge inspection  details at this time.',
                'data' => null,
            ], 500);
        }
    }

    public function store(StoreBridgeInspRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $userId = $validatedData['created_by'];
            if (empty($validatedData['insp_cd'])) {
                $validatedData['insp_cd'] =
                    'INSP-' . strtoupper(uniqid());
            }

            $data['created_by'] = $userId;
            $data['updated_by'] = $userId;

            $inspectionId = DB::table('maintenance.mtn_bridge_insp_details')->insertGetId($validatedData);

            return response()->json([
                'success' => true,
                'status_code' => 201,
                'message' => 'Bridge inspection details created successfully.',
                'data' => ['id' => $inspectionId],
            ], 201);
        } catch (Throwable $e) {
            Log::error('Failed to create bridge inspection details.', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Unable to create bridge inspection details at this time.',
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
                ->where('bridge_insp.id', $id)
                ->first();

            // Record not found
            if (!$inspection) {
                return response()->json([
                    'success' => false,
                    'status_code' => 404,
                    'message' => 'Bridge Inspection details not found.',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => 'Bridge Inspection details retrieved successfully.',
                'data' => $inspection,
            ], 200);
        } catch (Throwable $e) {

            Log::error('Failed to retrieve Bridge inspection details.', [
                'inspection_id' => $id ?? null,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Unable to retrieve Bridge inspection details at this time.',
                'data' => null,
            ], 500);
        }
    }
}
