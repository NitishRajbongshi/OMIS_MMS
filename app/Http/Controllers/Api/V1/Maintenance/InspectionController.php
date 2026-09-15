<?php

namespace App\Http\Controllers\Api\V1\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\StoreInspectionRequest;
use App\Http\Resources\Maintenance\InspectionDetailResource;
use App\Models\Maintenance\Inspection\MtnInspectionDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InspectionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = MtnInspectionDetail::query();

            // Optional filters — all safe no-ops if not passed
            if ($request->filled('insp_asset_type')) {
                $query->where('insp_asset_type', $request->input('insp_asset_type'));
            }

            if ($request->filled('insp_asset_id')) {
                $query->where('insp_asset_id', $request->input('insp_asset_id'));
            }

            if ($request->filled('insp_type_cd')) {
                $query->where('insp_type_cd', $request->input('insp_type_cd'));
            }

            if ($request->filled('cndtn_overall')) {
                $query->where('cndtn_overall', $request->input('cndtn_overall'));
            }

            if ($request->filled('is_defects_observed')) {
                $query->where('is_defects_observed', $request->input('is_defects_observed'));
            }

            if ($request->filled('date_from')) {
                $query->whereDate('insp_date', '>=', $request->input('date_from'));
            }

            if ($request->filled('date_to')) {
                $query->whereDate('insp_date', '<=', $request->input('date_to'));
            }

            $query->latest('insp_date');

            $perPage = (int) $request->input('per_page', 15);
            $perPage = $perPage > 0 && $perPage <= 100 ? $perPage : 15;

            $inspections = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => InspectionDetailResource::collection($inspections->items()),
                'meta' => [
                    'current_page' => $inspections->currentPage(),
                    'per_page' => $inspections->perPage(),
                    'total' => $inspections->total(),
                    'last_page' => $inspections->lastPage(),
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch inspection details list', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch inspection details.',
            ], 500);
        }
    }

    public function store(StoreInspectionRequest $request)
    {
        Log::info('Creating new inspection detail', [
            'user_id' => auth()->id(),
            'request_data' => $request->all(),
        ]);
        try {
            $inspection = DB::transaction(function () use ($request) {
                $data = $request->validated();
                // generate a unique inspection code if not provided
                if (empty($request['insp_cd'])) {
                    $data['insp_cd'] = 'INSP-' . strtoupper(uniqid());
                }

                $data['created_by'] = auth()->id();
                $data['updated_by'] = auth()->id();

                return MtnInspectionDetail::create($data);
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
            // Postgres unique_violation SQLSTATE = 23505
            if ($e->getCode() === '23505') {
                Log::warning('Duplicate insp_cd on inspection create', [
                    'insp_cd' => $request->input('insp_cd'),
                    'user_id' => auth()->id(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'An inspection with this insp_cd already exists.',
                    'error_code' => 'DUPLICATE_INSP_CD',
                ], 409); // Conflict
            }

            // Postgres foreign_key_violation = 23503, not_null_violation = 23502, etc.
            Log::error('Database error creating inspection', [
                'sql_state' => $e->getCode(),
                'message'   => $e->getMessage(),
                'user_id'   => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'A database error occurred while saving the inspection.',
                'error_code' => 'DB_ERROR',
            ], 422); // Unprocessable — likely a constraint tied to bad input data

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
            $inspection = MtnInspectionDetail::find($id);

            if (! $inspection) {
                return response()->json([
                    'success' => false,
                    'message' => "Inspection detail with id [{$id}] not found.",
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new InspectionDetailResource($inspection),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch inspection detail', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch inspection detail.',
            ], 500);
        }
    }
}
