<?php

namespace App\Http\Controllers\MMIS;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CompletedMaintenanceAssessmentController extends Controller
{
    /**
     * Display all completed maintenance assessments.
     */
    // public function index()
    // {
    //     try {
    //         $assessments = DB::table('maintenance.mtn_inspection_score as score')
    //             ->join('maintenance.mtn_inspection_details as inspection','inspection.id','=','score.inspection_id')
    //             ->leftJoin('public.asset_master_road_sub_assets as asset_type','asset_type.sub_asset_cd','=','inspection.insp_asset_type')
    //             ->select([
    //                 'score.id as assessment_id',
    //                 'score.inspection_id',
    //                 'inspection.insp_asset_id',
    //                 'inspection.insp_asset_type',
    //                 'inspection.insp_cd',
    //                 'inspection.insp_asset_location',
    //                 'inspection.insp_date',
    //                 'inspection.start_section',
    //                 'inspection.end_section',
    //                 'inspection.cndtn_overall',
    //                 'asset_type.sub_assets_descr as asset_type_name',
    //                 'score.mas_score',
    //                 'score.condition_score',
    //                 'score.criticality_score',
    //                 'score.mps_score',
    //                 'score.calculated_at',
    //                 'score.calculated_by',
    //                 'score.calculation_version',
    //                 'score.status',
    //             ])
    //             ->where('score.status', 'ACTIVE')
    //             ->orderByDesc('score.calculated_at')
    //             ->get();

    //         return view('mmis.completed_maintenance_assessment.index',compact('assessments'));

    //     } catch (\Throwable $exception) {
    //         Log::error('Completed maintenance assessments could not be loaded.',
    //             [
    //                 'exception' => $exception->getMessage(),
    //             ]
    //         );

    //         return view(
    //             'mmis.completed_maintenance_assessment.index',
    //             [
    //                 'assessments' => collect(),
    //             ]
    //         )->with(
    //             'error',
    //             'Completed maintenance assessment data could not be loaded.'
    //         );
    //     }
    // }


    public function index()
    {
        try {
            $assessments = DB::table('maintenance.mtn_inspection_score as score')
                ->join(
                    'maintenance.mtn_inspection_details as inspection',
                    'inspection.id',
                    '=',
                    'score.inspection_id'
                )
                ->leftJoin(
                    'public.asset_master_road_sub_assets as asset_type',
                    'asset_type.sub_asset_cd',
                    '=',
                    'inspection.insp_asset_type'
                )
                ->select([
                    'score.id as assessment_id',
                    'score.inspection_id',
                    'inspection.insp_asset_id',
                    'inspection.insp_asset_type',
                    'inspection.insp_cd',
                    'inspection.insp_asset_location',
                    'inspection.insp_date',
                    'inspection.start_section',
                    'inspection.end_section',
                    'inspection.cndtn_overall',
                    'asset_type.sub_assets_descr as asset_type_name',
                    'score.mas_score',
                    'score.condition_score',
                    'score.criticality_score',
                    'score.mps_score',
                    'score.calculated_at',
                    'score.calculated_by',
                    'score.calculation_version',
                    'score.status',
                ])
                ->where('score.status', 'ACTIVE')
                // Highest maintenance priority first
                ->orderByRaw('score.mps_score DESC NULLS LAST')
                ->orderByRaw('score.criticality_score DESC NULLS LAST')
                ->orderByDesc('score.calculated_at')
                ->get();
            Log::info("assessment" . $assessments);
            return view(
                'mmis.completed_maintenance_assessment.index',
                compact('assessments')
            );

            // $assessmentsByType = $assessments->groupBy(
            //     fn ($item) => (string) $item->insp_asset_type
            // );

            // return view(
            //     'mmis.completed_maintenance_assessment.index',
            //     compact('assessments', 'assessmentsByType')
            // );

        } catch (\Throwable $exception) {
            Log::error(
                'Completed maintenance assessments could not be loaded.',
                ['exception' => $exception->getMessage()]
            );

            return view(
                'mmis.completed_maintenance_assessment.index',
                ['assessments' => collect()]
            )->with(
                    'error',
                    'Completed maintenance assessment data could not be loaded.'
                );
        }
    }



    /**
     * Display one completed maintenance assessment.
     */
    public function show(int $assessment)
    {
        try {

            /*
             * ---------------------------------------------------------
             * 1. Assessment Header
             * ---------------------------------------------------------
             */

            $assessmentDetail = DB::table('maintenance.mtn_inspection_score as score')
                ->join('maintenance.mtn_inspection_details as inspection', 'inspection.id', '=', 'score.inspection_id')
                ->leftJoin('public.asset_master_road_sub_assets as asset_type', 'asset_type.sub_asset_cd', '=', 'inspection.insp_asset_type')
                ->select([
                    'score.id as assessment_id',
                    'score.inspection_id',

                    'score.mas_score',
                    'score.condition_score',
                    'score.criticality_score',
                    'score.mps_score',

                    'score.condition_factor',
                    'score.criticality_factor',
                    'score.calculation_version',
                    'score.calculated_at',
                    'score.calculated_by',

                    'inspection.insp_asset_id',
                    'inspection.insp_asset_type',
                    'inspection.insp_cd',
                    'inspection.insp_asset_location',
                    'inspection.insp_date',
                    'inspection.start_section',
                    'inspection.end_section',
                    'inspection.cndtn_overall',

                    'asset_type.sub_assets_descr as asset_type_name',
                ])
                ->where('score.id', $assessment)
                ->where('score.status', 'ACTIVE')
                ->first();

            abort_unless($assessmentDetail, 404);
            /*
             * ---------------------------------------------------------
             * 2. Parameter Details
             * ---------------------------------------------------------
             */

            $parameterDetails = DB::table('maintenance.mtn_inspection_score_details as detail')
                ->where('detail.inspection_score_id', $assessmentDetail->assessment_id)
                ->orderBy('detail.id')
                ->get();


            return view('mmis.completed_maintenance_assessment.show', compact('assessmentDetail', 'parameterDetails'));

        } catch (\Throwable $exception) {
            Log::error('Completed maintenance assessment details could not be loaded.', [
                'assessment_id' => $assessment,
                'exception' => $exception->getMessage(),
            ]);
            return redirect()->route('mmis.completed-maintenance-assessment.index')->with('error', 'Completed maintenance assessment could not be loaded.');
        }
    }
}