<?php

namespace App\Http\Controllers\Api\V1\Maintenance\Assessment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class MtnAssessmentController extends Controller
{
    public function index(string $assetType)
    {
        // Validate the asset type received from the URL.
        $validator = Validator::make(
            ['assetType' => $assetType],
            [
                'assetType' => ['required', 'integer', 'min:0'],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status_code' => 422,
                'message' => 'Invalid asset type provided.',
                'error_code' => 'INVALID_ASSET_TYPE',
                'errors' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        try {
            $assessments = DB::table('maintenance.mtn_inspection_score as assessment')
                ->join(
                    'maintenance.mtn_inspection_details as insp',
                    'insp.id',
                    '=',
                    'assessment.inspection_id'
                )
                ->join(
                    'public.asset_master_road_sub_assets as sub_asset',
                    'sub_asset.sub_asset_cd',
                    '=',
                    'insp.insp_asset_type'
                )
                ->where('insp.insp_asset_type', (int) $assetType)
                ->select([
                    'assessment.id as assessment_id',
                    'assessment.inspection_id',
                    'assessment.mas_score',
                    'assessment.condition_score',
                    'assessment.criticality_score',
                    'assessment.mps_score',
                    'assessment.condition_factor',
                    'assessment.criticality_factor',
                    'assessment.calculation_version',
                    'assessment.calculated_at',
                    'assessment.calculated_by',
                    'assessment.status',
                    'assessment.created_at',
                    'insp.insp_cd',
                    'insp.insp_asset_type',
                    'insp.insp_asset_id',
                    'insp.insp_asset_location',
                    'insp.insp_date',
                    'insp.start_section',
                    'insp.end_section',
                    'insp.cndtn_overall',
                    'sub_asset.sub_assets_descr as asset_type_name',
                ])
                ->orderByDesc('assessment.created_at')
                ->orderByDesc('assessment.id')
                ->get();

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => $assessments->isEmpty()
                    ? 'No assessment details found for the specified asset type.'
                    : 'Assessment details fetched successfully.',
                'data' => $assessments,
            ], 200);
        } catch (Throwable $e) {
            Log::error('Failed to fetch assessment details.', [
                'asset_type' => $assetType,
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Unable to fetch assessment details at this time.',
                'error_code' => 'ASSESSMENT_FETCH_FAILED',
                'data' => null,
            ], 500);
        }
    }


    public function show(string $assessmentId)
    {
        // Validate the assessment ID.
        $validator = Validator::make(
            ['assessmentId' => $assessmentId],
            [
                'assessmentId' => ['required', 'integer', 'min:1'],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'status_code' => 422,
                'message' => 'Invalid assessment ID provided.',
                'error_code' => 'INVALID_ASSESSMENT_ID',
                'errors' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        try {
            // Fetch the main assessment.
            $assessment = DB::table(
                'maintenance.mtn_inspection_score as assessment'
            )
                ->join(
                    'maintenance.mtn_inspection_details as insp',
                    'insp.id',
                    '=',
                    'assessment.inspection_id'
                )
                ->join(
                    'public.asset_master_road_sub_assets as sub_asset',
                    'sub_asset.sub_asset_cd',
                    '=',
                    'insp.insp_asset_type'
                )
                ->where('assessment.id', (int) $assessmentId)
                ->select([
                    'assessment.id as assessment_id',
                    'assessment.inspection_id',
                    'assessment.mas_score',
                    'assessment.condition_score',
                    'assessment.criticality_score',
                    'assessment.mps_score',
                    'assessment.condition_factor',
                    'assessment.criticality_factor',
                    'assessment.calculation_version',
                    'assessment.calculated_at',
                    'assessment.calculated_by',
                    'assessment.status',
                    'assessment.created_at',
                    'insp.insp_cd',
                    'insp.insp_asset_type',
                    'insp.insp_asset_id',
                    'insp.insp_asset_location',
                    'insp.insp_date',
                    'insp.start_section',
                    'insp.end_section',
                    'insp.cndtn_overall',
                    'sub_asset.sub_assets_descr as asset_type_name',
                ])
                ->first();

            if (!$assessment) {
                return response()->json([
                    'success' => false,
                    'status_code' => 404,
                    'message' => 'Assessment not found.',
                    'error_code' => 'ASSESSMENT_NOT_FOUND',
                    'data' => null,
                ], 404);
            }

            // Fetch all parameter details for this assessment.
            $details = DB::table(
                'maintenance.mtn_inspection_score_details'
            )
                ->where('inspection_score_id', (int) $assessmentId)
                ->select([
                    'id',
                    'inspection_score_id',
                    'parameter_set_id',
                    'parameter_id',
                    'parameter_code',
                    'parameter_name',
                    'score_category',
                    'parameter_value',
                    'maximum_range',
                    'weightage',
                    'normalized_score',
                    'weighted_score',
                    'created_at',
                    'created_by',
                ])
                ->orderBy('id')
                ->get();

            // Attach the one-to-many details to the assessment.
            $assessment->parameters = $details;

            return response()->json([
                'success' => true,
                'status_code' => 200,
                'message' => 'Assessment details fetched successfully.',
                'data' => $assessment,
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Failed to fetch assessment details.', [
                'assessment_id' => $assessmentId,
                'exception' => $e,
            ]);

            return response()->json([
                'success' => false,
                'status_code' => 500,
                'message' => 'Unable to fetch assessment details at this time.',
                'error_code' => 'ASSESSMENT_FETCH_FAILED',
                'data' => null,
            ], 500);
        }
    }
}
