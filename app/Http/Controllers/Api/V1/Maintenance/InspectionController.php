<?php

namespace App\Http\Controllers\Api\V1\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\StoreInspectionRequest;
use App\Models\Maintenance\Inspection\MtnInspectionDetail;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InspectionController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => 'Successfully fetched inspection details.',
        ]);
    }

    public function store(StoreInspectionRequest $request)
    {
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
}
