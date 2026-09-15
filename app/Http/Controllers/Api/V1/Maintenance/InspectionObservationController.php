<?php

namespace App\Http\Controllers\Api\v1\maintenance;

use App\Http\Controllers\Controller;
use App\Models\Maintenance\Inspection\MtnInspectionDetail;
use App\Models\Maintenance\Inspection\MtnInspObservationDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InspectionObservationController extends Controller
{
    public function getObservations(int $insp_id)
    {
        try {
            if ($insp_id <= 0) {
                return response()->json([
                    'success' => false,
                    'status_code' => 422,
                    'message' => 'Invalid inspection ID.',
                    'error_code' => 'INVALID_INSP_ID',
                    'data' => null,
                ], 422);
            }

            $inspectionExists = MtnInspectionDetail::where('id', $insp_id)
                ->exists();

            if (!$inspectionExists) {
                return response()->json([
                    'success' => false,
                    'status_code' => 404,
                    'message' => 'Inspection not found.',
                    'error_code' => 'INSPECTION_NOT_FOUND',
                    'data' => null,
                ], 404);
            }

            $observations = DB::table('maintenance.mtn_insp_observation_details as obj')
                ->select('obj.*', 'act.activity_title', 'act.activity_descr', 'grading.grading_descr')
                ->leftJoin('maintenance.master_mtn_item_activities as act', 'obj.activity_cd', '=', 'act.activity_cd')
                ->leftJoin('maintenance.master_mtn_grading_details as grading', 'obj.grading_cd', '=', 'grading.grading_cd')
                ->where('insp_id', $insp_id)
                ->orderBy('id')
                ->get();

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => $observations->isEmpty()
                    ? 'No observations found for this inspection.'
                    : 'Inspection observations retrieved successfully.',
                'data' => $observations,
                'count' => $observations->count(),
            ], 200);
        } catch (QueryException $e) {
            Log::error('Database error while retrieving inspection observations.', [
                'insp_id' => $insp_id,
                'sql_state' => $e->getCode(),
                'message' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'A database error occurred while retrieving observations.',
                'error_code' => 'DB_ERROR',
                'data' => null,
            ], 500);
        } catch (Throwable $e) {
            Log::error('Unexpected error while retrieving inspection observations.', [
                'insp_id' => $insp_id,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'An unexpected error occurred while retrieving observations.',
                'error_code' => 'SERVER_ERROR',
                'data' => null,
            ], 500);
        }
    }
}
