<?php

namespace App\Http\Controllers\MMIS;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MaintenanceAssessmentController extends Controller
{
    /**
     * Maintenance Assessment landing page.
     */
    // public function index()
    // {
    //     try {
    //         /*
    //         * ============================================================
    //         * Latest inspection for each asset
    //         * ============================================================
    //         */

    //         $latestInspectionIds = DB::table('maintenance.mtn_inspection_details')
    //             // ->selectRaw('DISTINCT ON (insp_asset_id) id')
    //             ->selectRaw(
    //                 'DISTINCT ON (insp_asset_type, insp_asset_id) id'
    //             )
    //             ->whereNotNull('insp_asset_type')
    //             ->whereNotNull('insp_asset_id')
    //             ->orderBy('insp_asset_type')
    //             ->orderBy('insp_asset_id')
    //             ->orderByDesc('insp_date')
    //             ->orderByDesc('id');
    //         /*
    //         * ============================================================
    //         * Count ONLY assets whose latest inspection
    //         * has NOT been assessed.
    //         * ============================================================
    //         */    
    //         $assetTypes = DB::table('maintenance.mtn_inspection_details as inspection')
    //         ->leftJoin('public.asset_master_road_sub_assets as asset_type','asset_type.sub_asset_cd','=','inspection.insp_asset_type')
    //         ->leftJoin('maintenance.mtn_inspection_score as score',
    //             function ($join) {
    //                 $join->on('score.inspection_id','=','inspection.id')
    //                 ->where('score.status','=','ACTIVE');
    //             }
    //         )
    //         ->whereIn('inspection.id',$latestInspectionIds)
    //         ->whereNull('score.id')
    //         ->selectRaw(
    //             'inspection.insp_asset_type as code,
    //              COALESCE(
    //                 asset_type.sub_assets_descr,
    //                 inspection.insp_asset_type
    //              ) as description,
    //              COUNT(DISTINCT inspection.insp_asset_id) as asset_count'
    //         )
    //         ->whereNotNull('inspection.insp_asset_type')
    //         ->whereNotNull('inspection.insp_asset_id')
    //         ->groupBy('inspection.insp_asset_type','asset_type.sub_assets_descr')
    //         ->orderBy('description')
    //         ->get();
    //         return view('mmis.maintenance_assessment.index',compact('assetTypes'));
    //     } catch (\Throwable $exception) {

    //         Log::error(
    //             'Maintenance assessment types could not be loaded.',
    //             [
    //                 'exception' => $exception->getMessage()
    //             ]
    //         );

    //         return view(
    //             'mmis.maintenance_assessment.index',
    //             [
    //                 'assetTypes' => collect()
    //             ]
    //         )->with(
    //                 'error',
    //                 'Maintenance assessment data could not be loaded.'
    //             );
    //     }
    // }

    public function index()
    {
        try {

            $assetTypes = DB::table('maintenance.mtn_inspection_details as inspection')
                ->leftJoin(
                    'maintenance.mtn_inspection_score as score',
                    function ($join) {

                        $join->on(
                            'score.inspection_id',
                            '=',
                            'inspection.id'
                        )
                            ->where(
                                'score.status',
                                '=',
                                'ACTIVE'
                            );
                    }
                )

                ->leftJoin('public.asset_master_road_sub_assets as asset_type', 'asset_type.sub_asset_cd', '=', 'inspection.insp_asset_type')
                /*
                 * Only inspections which have NOT
                 * been assessed yet.
                 */
                ->whereNull('score.id')
                ->whereNotNull('inspection.insp_asset_type')
                ->whereNotNull('inspection.insp_asset_id')
                ->selectRaw(
                    'inspection.insp_asset_type as code,
                    COALESCE(
                        asset_type.sub_assets_descr,
                        inspection.insp_asset_type
                    ) as description,

                    COUNT(*) as asset_count'
                )
                ->groupBy(
                    'inspection.insp_asset_type',
                    'asset_type.sub_assets_descr'
                )
                ->orderBy('description')
                ->get();
            return view(
                'mmis.maintenance_assessment.index',
                compact('assetTypes')
            );
        } catch (\Throwable $exception) {

            Log::error(
                'Maintenance assessment types could not be loaded.',
                [
                    'exception' => $exception->getMessage()
                ]
            );

            return view(
                'mmis.maintenance_assessment.index',
                [
                    'assetTypes' => collect()
                ]
            )->with(
                'error',
                'Maintenance assessment data could not be loaded.'
            );
        }
    }

    /**
     * Display pending inspections for selected asset type.
     */
    public function assets(string $assetType)
    {
        try {

            /*
             * ============================================================
             * Asset Type
             * ============================================================
             */
            $assetTypeDetail = DB::table(
                'public.asset_master_road_sub_assets'
            )
                ->select(
                    'sub_asset_cd as code',
                    'sub_assets_descr as description'
                )
                ->where(
                    'sub_asset_cd',
                    $assetType
                )
                ->first();


            /*
             * ============================================================
             * Get ALL PENDING INSPECTIONS
             *
             * IMPORTANT:
             * Do NOT use DISTINCT ON() here.
             *
             * One asset can have multiple inspections:
             *
             * PHMR0001
             *   INSP-2026-0001 -> completed
             *   INSP-2026-0004 -> pending
             *   INSP-2026-0008 -> pending
             *
             * These are separate assessment records.
             * ============================================================
             */
            $assets = DB::table(
                'maintenance.mtn_inspection_details as inspection'
            )

                /*
                 * --------------------------------------------------------
                 * Join assessment score
                 * --------------------------------------------------------
                 */
                ->leftJoin(
                    'maintenance.mtn_inspection_score as score',
                    function ($join) {

                        $join->on(
                            'score.inspection_id',
                            '=',
                            'inspection.id'
                        )
                            ->where(
                                'score.status',
                                '=',
                                'ACTIVE'
                            );
                    }
                )

                /*
                 * --------------------------------------------------------
                 * Selected Asset Type
                 * --------------------------------------------------------
                 */
                ->where(
                    'inspection.insp_asset_type',
                    $assetType
                )

                /*
                 * --------------------------------------------------------
                 * ONLY PENDING INSPECTIONS
                 *
                 * If an inspection already has an assessment score,
                 * it will NOT appear here.
                 * --------------------------------------------------------
                 */
                ->whereNull(
                    'score.id'
                )

                ->whereNotNull(
                    'inspection.insp_asset_id'
                )

                ->whereNotNull(
                    'inspection.insp_cd'
                )

                /*
                 * --------------------------------------------------------
                 * Select inspection information
                 * --------------------------------------------------------
                 */
                ->select([
                    'inspection.id as inspection_id',

                    'inspection.insp_cd',

                    'inspection.insp_asset_id',

                    'inspection.insp_asset_type',

                    'inspection.insp_asset_location',

                    'inspection.insp_date',

                    'inspection.start_section',

                    'inspection.end_section',

                    'inspection.cndtn_overall',

                ])

                /*
                 * Most recent inspection first
                 */
                ->orderBy(
                    'inspection.insp_date',
                    'asc'
                )

                ->orderByDesc(
                    'inspection.id'
                )

                ->get();


            /*
             * ============================================================
             * 404 only if asset type itself doesn't exist
             * ============================================================
             */
            if (!$assetTypeDetail) {
                abort(404);
            }


            $assetTypeName = $assetTypeDetail->description ?? $assetType;
            return view('mmis.maintenance_assessment.assets', compact('assets', 'assetType', 'assetTypeName'));
        } catch (\Throwable $exception) {

            Log::error(
                'Maintenance assessment asset list could not be loaded.',
                [
                    'asset_type' => $assetType,
                    'exception' => $exception->getMessage(),
                ]
            );

            return redirect()->route('mmis.maintenance-assessment.index')
                ->with('error', 'Inspections for the selected asset type could not be loaded.');
        }
    }


    /**
     * Display Maintenance Assessment details.
     */
    public function show(int $inspection)
    {
        try {

            /*
             * ---------------------------------------------------------
             * 1. Get inspection details
             * ---------------------------------------------------------
             */
            // $inspectionDetail = DB::table('maintenance.mtn_inspection_details as inspection')
            //     ->leftJoin('public.asset_master_road_sub_assets as asset_type','asset_type.sub_asset_cd','=','inspection.insp_asset_type')
            //     ->select('inspection.*','asset_type.sub_assets_descr as asset_type_name','asset_type.dept_cd as asset_dept_cd')
            //     ->where('inspection.id',$inspection)
            //     ->first();
            $inspectionDetail = DB::table('maintenance.mtn_inspection_details as inspection')
                ->leftJoin('public.asset_master_road_sub_assets as asset_type', 'asset_type.sub_asset_cd', '=', 'inspection.insp_asset_type')
                ->leftJoin('public.asset_road_details as road', 'road.rd_system_id', '=', 'inspection.insp_asset_id')
                ->leftJoin('public.asset_master_road_category as road_category', 'road_category.rd_catg_cd', '=', 'road.rd_category_cd')
                ->select(
                    'inspection.*',
                    'asset_type.sub_assets_descr as asset_type_name',
                    'asset_type.dept_cd as asset_dept_cd',
                    'road.rd_category_cd',
                    'road_category.rd_catg_descr',
                    'road_category.maintenance_score as road_category_score'
                )
                ->where('inspection.id', $inspection)
                ->first();
            abort_unless($inspectionDetail, 404);

            // ============================================================
            // CHECK WHETHER ASSESSMENT IS ALREADY COMPLETED
            // ============================================================
            $existingScore = DB::table('maintenance.mtn_inspection_score')
                ->where('inspection_id', $inspectionDetail->id)
                ->where('status', 'ACTIVE')
                ->first();

            if ($existingScore) {
                return redirect()->route('mmis.maintenance-assessment.assets', $inspectionDetail->insp_asset_type)
                    ->with('info', 'Maintenance assessment for this asset has already been completed.');
            }
            /*
             * ---------------------------------------------------------
             * 2. Get inspection observations
             * ---------------------------------------------------------
             */
            $observations = DB::table('maintenance.mtn_insp_observation_details as observation')
                ->where('observation.insp_id', $inspectionDetail->id)
                ->select([
                    'observation.activity_cd',
                    'observation.obsrv_desc',
                    'observation.grading_cd',
                    'observation.obsrv_weightage',
                    'observation.parameter_id',
                ])
                ->orderBy('observation.id')
                ->get();


            /*
             * ---------------------------------------------------------
             * 3. Get PCI
             * ---------------------------------------------------------
             */
            $pci = null;

            if (!empty($inspectionDetail->pci_section_cd)) {
                $pci = DB::table('public.asset_road_pavement_condition_indexes')
                    ->where('pci_section_cd', $inspectionDetail->pci_section_cd)
                    ->first();
            }


            /*
             * ---------------------------------------------------------
             * 4. Get habitation
             * ---------------------------------------------------------
             */
            $assetType = (string) $inspectionDetail->insp_asset_type;
            $assetCd = $inspectionDetail->insp_asset_id;
            $roadSystemId = null;
            $habitation = null;
            $parentRoad = null;
            switch ($assetType) {
                // Road and National Highway: the asset ID is the road ID.
                case '10':
                case '8':
                    $roadSystemId = $assetCd;
                    break;

                // CD Works
                case '0':
                    $roadSystemId = DB::table('public.asset_road_cdwork_details')
                        ->where('rd_cdwork_cd', $assetCd)
                        ->value('rd_system_id');
                    break;

                // Bridge
                case '1':
                    $roadSystemId = DB::table('public.asset_road_bridge_details')
                        ->where('rd_bridge_cd', $assetCd)
                        ->value('rd_system_id');
                    break;

                // Pavement
                case '2':
                    $roadSystemId = DB::table('public.asset_road_pavement_details')
                        ->where('rd_pavement_cd', $assetCd)
                        ->value('rd_system_id');
                    break;

                // Surface Type
                case '3':
                    $roadSystemId = DB::table('public.asset_road_surface_type_details')
                        ->where('rd_surface_cd', $assetCd)
                        ->value('rd_system_id');
                    break;

                // Habitation
                case '4':
                    $roadSystemId = DB::table('public.asset_road_habitation_details')
                        ->where('habitation_cd', $assetCd)
                        ->value('rd_system_id');
                    break;

                // Pavement Condition Index
                case '9':
                    $roadSystemId = DB::table(
                        'public.asset_road_pavement_condition_indexes'
                    )
                        ->where('pci_section_cd', $assetCd)
                        ->value('rd_system_id');
                    break;

                // Distress
                case '11':
                    $roadSystemId = DB::table('public.asset_road_distress_details')
                        ->where('rd_distress_cd', $assetCd)
                        ->value('rd_system_id');
                    break;

                // Protection Wall
                case '12':
                    $roadSystemId = DB::table('public.asset_protection_wall_details')
                        ->where('protection_wall_cd', $assetCd)
                        ->value('rd_system_id');
                    break;
            }

            if (!empty($roadSystemId)) {
                $habitation = DB::table('public.asset_road_habitation_details')
                    ->where('rd_system_id', $roadSystemId)
                    ->selectRaw('
                            AVG(habitation_score) AS habitation_score,
                            COUNT(*) AS total_habitations,
                            COUNT(habitation_score) AS scored_habitations
                        ')
                    ->first();

                $parentRoad = DB::table('public.asset_road_details')
                    ->select('rd_system_id', 'rd_name')
                    ->where('rd_system_id', $roadSystemId)
                    ->first();
            }

            /*
             * ---------------------------------------------------------
             * 5. Get Asset Criticality
             * ---------------------------------------------------------
             */
            $criticality = DB::table('maintenance.master_asset_criticality_indexes')
                ->where('asset_type', $inspectionDetail->insp_asset_type)
                ->where('asset_id', $inspectionDetail->insp_asset_id)
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->first();


            /*
             * ---------------------------------------------------------
             * 6. Calc observation values
             * ---------------------------------------------------------
             */
            $totalWeight = $observations->sum(fn($item) => (int) $item->obsrv_weightage);
            $averageWeight = $observations->isNotEmpty() ? round($totalWeight / $observations->count(), 2) : null;
            $unsatisfactoryWeight = $observations
                ->filter(fn($item) => strtoupper((string) $item->grading_cd) === 'U')
                ->sum(fn($item) => (int) $item->obsrv_weightage);
            $inspectionCriticality = $totalWeight > 0 ? round(($unsatisfactoryWeight / $totalWeight) * 100, 2) : null;
            $departmentCd = $inspectionDetail->asset_dept_cd;
            $parameters = collect();


            if (!empty($departmentCd)) {
                $parameters = DB::table('maintenance.master_set_of_mtn_parameters_dept_wise as parameter_set')
                    ->join('maintenance.master_mtn_parameter_master as parameter', 'parameter.id', '=', 'parameter_set.parameter_id')
                    ->leftJoin('maintenance.master_mtn_parameter_range as parameter_range', 'parameter_range.parameter_id', '=', 'parameter.id')
                    ->leftJoin(
                        'maintenance.master_each_mtn_parameters_weightage as parameter_weightage',
                        function ($join) {
                            $join->on('parameter_weightage.mtn_parameter_set_id', '=', 'parameter_set.id')
                                ->on('parameter_weightage.parameter_id', '=', 'parameter.id');
                        }
                    )
                    ->where('parameter_set.dept_cd', $departmentCd)
                    ->where('parameter_set.asset_type_cd', $inspectionDetail->insp_asset_type)
                    ->where('parameter.status', 'ACTIVE')
                    ->where(function ($query) {
                        $query->where('parameter_range.status', 'ACTIVE')
                            ->orWhereNull('parameter_range.status');
                    })
                    ->select([
                        'parameter_set.id as parameter_set_id',
                        'parameter.id as parameter_id',
                        'parameter.parameter_code',
                        'parameter.parameter_name',
                        'parameter.parameter_type',
                        'parameter.description',
                        'parameter_range.lower_range',
                        'parameter_range.upper_range',
                        'parameter_weightage.weightage',
                    ])
                    ->orderBy('parameter_set.id')->get();
            }
            /*
             * ---------------------------------------------------------
             * 9. Current values for known parameters
             * ---------------------------------------------------------
             *
             * The PARAMETER SET remains completely dynamic.
             *
             * These values are only the currently available
             * assessment inputs.
             *
             */
            $parameterValues = [
                'FIELD_OBSERVATION_WEIGHTAGE' => $averageWeight,
                'PCI' => $pci->pci_value ?? null,
                'HABITATION_INDEX' => $habitation->habitation_score ?? null,
                'CRITICALITY_INDEX' => $criticality->criticality_index ?? null,
                'ROAD_CONNECTIVITY' => null,
                'ROAD_CATEGORY' => $inspectionDetail->road_category_score ?? null,
                'PRIORITY_INDEX' => null,
            ];


            /*
             * Add current value to each dynamically
             * configured parameter.
             */
            $parameters->each(
                function ($parameter) use ($parameterValues) {
                    $parameter->current_value =
                        $parameterValues[$parameter->parameter_code] ?? null;
                }
            );


            /*
             * ---------------------------------------------------------
             * 10. Return View
             * ---------------------------------------------------------
             */
            return view(
                'mmis.maintenance_assessment.show',
                compact(
                    'inspectionDetail',
                    'observations',
                    'pci',
                    'habitation',
                    'criticality',
                    'parameters',
                    'totalWeight',
                    'averageWeight',
                    'unsatisfactoryWeight',
                    'inspectionCriticality',
                    'parentRoad'
                )
            );
        } catch (\Throwable $exception) {

            Log::error(
                'Maintenance assessment could not be loaded.',
                [
                    'inspection_id' => $inspection,
                    'exception' => $exception->getMessage(),
                ]
            );
            return redirect()->route('mmis.maintenance-assessment.index')->with('error', 'The selected assessment could not be loaded.');
        }
    }


    /**
     * Calc Maintenance Assessment Score.
     *
     * Formula:
     *
     * Normalized Score =
     *      (Parameter Value / Maximum Range) * 100
     *
     * Weighted Score =
     *      Normalized Score * Weightage
     *
     * Total Maintenance Assessment Score =
     *      SUM(Weighted Score) / 100
     */

    /**
     * Main calculation function
     *
     * Calls both:
     * 1. MAS method
     * 2. MPS method
     */
    public function calculate(Request $request)
    {
        $parameters = $request->input('parameters', []);

        if (empty($parameters) || !is_array($parameters)) {
            return response()->json([
                'success' => false,
                'message' => 'No maintenance parameters received.'
            ], 422);
        }
        /*
        |--------------------------------------------------------------------------
        | Existing MAS Method
        |--------------------------------------------------------------------------
        */
        $masResult = $this->mas_method($parameters);
        /*
        |--------------------------------------------------------------------------
        | New MPS Method
        |--------------------------------------------------------------------------
        */

        $mpsResult = $this->mps_method($parameters);


        /*
        |--------------------------------------------------------------------------
        | Return Result
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            /*
             * Existing MAS
             */
            'total_score' => $masResult['mas'],
            'mas' => $masResult['mas'],
            'mas_calculation_details' => $masResult['details'],

            /*
             * New MPS
             */
            'condition_score' => $mpsResult['condition_score'],
            'criticality_score' => $mpsResult['criticality_score'],
            'mps' => $mpsResult['mps'],

            /*
             * Optional details
             */
            'mas_details' => $masResult['details'],
            'mps_details' => $mpsResult['details'],
            'mps_calculation_details' => $mpsResult['details'],
        ]);
    }


    /**
     * =========================================================================
     * MAS (Maintenance Assessment Score) METHOD
     * =========================================================================
     * Formula:
     *
     * Normalized Score = (Value / Maximum Range) * 100
     *
     * Weighted Score = Normalized Score * Weightage
     *
     * MAS = SUM(Weighted Score) / 100
     *
     */
    private function mas_method(array $parameters)
    {
        $masWeightedTotal = 0;
        $details = [];
        foreach ($parameters as $parameter) {
            $parameterCode = $parameter['parameter_code'] ?? null;
            $value = array_key_exists('value', $parameter) ? (float) $parameter['value'] : null;
            $maximumRange = array_key_exists('maximum_range', $parameter) ? (float) $parameter['maximum_range'] : null;
            $weightage = array_key_exists('weightage', $parameter) ? (float) $parameter['weightage'] : null;
            /*
            |--------------------------------------------------------------------------
            | Skip invalid parameter
            |--------------------------------------------------------------------------
            */

            if (empty($parameterCode) || $value === null || $maximumRange === null || $weightage === null) {
                continue;
            }


            if ($maximumRange <= 0) {
                continue;
            }
            $normalizedScore = ($value / $maximumRange) * 100;
            /*
            |--------------------------------------------------------------------------
            | Keep between 0 and 100
            |--------------------------------------------------------------------------
            */

            $normalizedScore = max(0, min(100, $normalizedScore));
            /*
            |--------------------------------------------------------------------------
            | Weighted Score
            |--------------------------------------------------------------------------
            */

            $weightedScore = $normalizedScore * $weightage;
            /*
            |--------------------------------------------------------------------------
            | Add to MAS total
            |--------------------------------------------------------------------------
            */

            $masWeightedTotal += $weightedScore;
            /*
            |--------------------------------------------------------------------------
            | Details
            |--------------------------------------------------------------------------
            */

            $details[] = [
                'parameter_code' => $parameterCode,
                'value' => $value,
                'maximum_range' => $maximumRange,
                'weightage' => $weightage,
                'normalized_score' => round($normalizedScore, 4),
                'weighted_score' => round($weightedScore, 4),
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Final MAS
        |--------------------------------------------------------------------------
        */

        $mas = $masWeightedTotal / 100;


        return [
            'mas' => round($mas, 2),

            'details' => $details,
        ];
    }


    /**
     * =========================================================================
     * MPS (Maintenance Priority Score) METHOD
     * =========================================================================
     *
     * Maintenance Priority Score
     *
     * 1. Calc Condition Score
     * 2. Calc Criticality Score
     * 3. Calc MPS
     *
     * Condition Score:SUM(Normalized Score × Weightage)/SUM(Condition Weightage)
     *
     * Criticality Score: SUM(Normalized Score × Weightage)/SUM(Criticality Weightage)
     *
     * MPS: 60% × (100 - Condition Score) + 40% × Criticality Score
     *
     */
    private function mps_method(array $parameters)
    {
        /*
        |--------------------------------------------------------------------------
        | Parameters belonging to CONDITION
        |--------------------------------------------------------------------------
        */

        $conditionParameters = [
            'PCI',
            'FIELD_OBSERVATION_WEIGHTAGE',
        ];

        /*
        |--------------------------------------------------------------------------
        | Parameters belonging to CRITICALITY
        |--------------------------------------------------------------------------
        */

        $criticalityParameters = [
            'CRITICALITY_INDEX',
            'ROAD_CATEGORY',
            'HABITATION_INDEX',
            'ROAD_CONNECTIVITY',
            'PRIORITY_INDEX',
        ];


        /*
        |--------------------------------------------------------------------------
        | Variables
        |--------------------------------------------------------------------------
        */

        $conditionWeightedTotal = 0;
        $conditionWeightTotal = 0;

        $criticalityWeightedTotal = 0;
        $criticalityWeightTotal = 0;

        $details = [];

        /*
        |--------------------------------------------------------------------------
        | Process parameters
        |--------------------------------------------------------------------------
        */

        foreach ($parameters as $parameter) {

            $parameterCode = $parameter['parameter_code'] ?? null;

            $value = array_key_exists('value', $parameter) ? (float) $parameter['value'] : null;

            $maximumRange = array_key_exists('maximum_range', $parameter) ? (float) $parameter['maximum_range'] : null;

            $weightage = array_key_exists('weightage', $parameter) ? (float) $parameter['weightage'] : null;

            /*
            |--------------------------------------------------------------------------
            | Skip invalid parameter
            |--------------------------------------------------------------------------
            */

            if (empty($parameterCode) || $value === null || $maximumRange === null || $weightage === null) {
                continue;
            }


            if ($maximumRange <= 0) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Normalize
            |--------------------------------------------------------------------------
            */

            $normalizedScore = ($value / $maximumRange) * 100;

            /*
            |--------------------------------------------------------------------------
            | Keep between 0 and 100
            |--------------------------------------------------------------------------
            */

            $normalizedScore = max(0, min(100, $normalizedScore));

            /*
            |--------------------------------------------------------------------------
            | Weighted Score
            |--------------------------------------------------------------------------
            */

            $weightedScore = $normalizedScore * $weightage;

            /*
            |--------------------------------------------------------------------------
            | CONDITION
            |--------------------------------------------------------------------------
            */

            if (in_array($parameterCode, $conditionParameters, true)) {
                $conditionWeightedTotal += $weightedScore;
                $conditionWeightTotal += $weightage;
            }


            /*
            |--------------------------------------------------------------------------
            | CRITICALITY
            |--------------------------------------------------------------------------
            */

            if (in_array($parameterCode, $criticalityParameters, true)) {
                $criticalityWeightedTotal += $weightedScore;
                $criticalityWeightTotal += $weightage;
            }


            /*
            |--------------------------------------------------------------------------
            | Details
            |--------------------------------------------------------------------------
            */

            $category = 'OTHER';

            if (in_array($parameterCode, $conditionParameters, true)) {
                $category = 'CONDITION';
            } elseif (in_array($parameterCode, $criticalityParameters, true)) {
                $category = 'CRITICALITY';
            }


            $details[] = [
                'parameter_code' => $parameterCode,
                'category' => $category,
                'value' => $value,
                'maximum_range' => $maximumRange,
                'weightage' => $weightage,
                'normalized_score' => round($normalizedScore, 4),
                'weighted_score' => round($weightedScore, 4),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | CONDITION SCORE
        |--------------------------------------------------------------------------
        */

        $conditionScore = null;

        if ($conditionWeightTotal > 0) {
            $conditionScore = $conditionWeightedTotal / $conditionWeightTotal;
        }


        /*
        |--------------------------------------------------------------------------
        | CRITICALITY SCORE
        |--------------------------------------------------------------------------
        */

        $criticalityScore = null;

        if ($criticalityWeightTotal > 0) {
            $criticalityScore = $criticalityWeightedTotal / $criticalityWeightTotal;
        }


        /*
        |--------------------------------------------------------------------------
        | MPS
        |--------------------------------------------------------------------------
        */

        $mps = null;


        /*
        |--------------------------------------------------------------------------
        | MPS Weights
        |--------------------------------------------------------------------------
        */

        $conditionFactor = 0.60;
        $criticalityFactor = 0.40;

        if ($conditionScore !== null && $criticalityScore !== null) {

            /*
             * Poor condition = higher maintenance priority
             *
             * Therefore:
             *
             * Condition Priority = 100 - Condition Score
             */
            $conditionPriority = 100 - $conditionScore;
            $mps = ($conditionFactor * $conditionPriority) + ($criticalityFactor * $criticalityScore);
        }

        /*
        |--------------------------------------------------------------------------
        | Return MPS result
        |--------------------------------------------------------------------------
        */

        return [
            'condition_score' => $conditionScore !== null ? round($conditionScore, 2) : null,
            'criticality_score' => $criticalityScore !== null ? round($criticalityScore, 2) : null,
            'mps' => $mps !== null ? round($mps, 2) : null,
            'details' => $details,
        ];
    }


    /**
     * Resolve the physical maintainable asset information
     * from the inspection asset type and asset code.
     *
     * Returns:
     * [
     *     'asset_name'      => string,
     *     'parent_asset_cd' => string|null
     * ]
     */
    private function resolveMaintainableAsset($assetType, $assetCd)
    {
        $assetName = null;
        $parentAssetCd = null;

        switch ((string) $assetType) {
            case '10':
                $asset = DB::table('public.asset_road_details')->where('rd_system_id', $assetCd)->first();
                if ($asset) {
                    $assetName = $asset->rd_name ?? $assetCd;
                    $parentAssetCd = null;
                }
                break;
            case '0':
                $asset = DB::table('public.asset_road_cdwork_details')->where('rd_cdwork_cd', $assetCd)->first();
                if ($asset) {
                    $assetName = $asset->asset_name ?? $assetCd;
                    $parentAssetCd = $asset->rd_system_id ?? null;
                }
                break;
            case '1':

                $asset = DB::table('public.asset_road_bridge_details')->where('rd_bridge_cd', $assetCd)->first();
                if ($asset) {
                    $assetName = $assetCd . ' - ' . ($asset->bridge_name ?? null);
                    $parentAssetCd = $asset->rd_system_id ?? null;
                }
                break;
            case '2':
                $asset = DB::table('public.asset_road_pavement_details')
                    ->where('rd_pavement_cd', $assetCd)
                    ->first();

                if ($asset) {
                    $assetName = $assetCd . ' - ' . ($asset->asset_name ?? null);
                    $parentAssetCd = $asset->rd_system_id ?? null;
                }
                break;
            case '3':
                $asset = DB::table('public.asset_road_surface_type_details')->where('rd_surface_cd', $assetCd)->first();
                if ($asset) {
                    $assetName = $assetCd . ' - ' . ($asset->asset_name ?? null);
                    $parentAssetCd = $asset->rd_system_id ?? null;
                }

                break;
            case '4':
                $asset = DB::table('public.asset_road_habitation_details')
                    ->where('habitation_cd', $assetCd)->first();

                if ($asset) {
                    $assetName = $assetCd . ' - ' . ($asset->village_name ?? null);
                    $parentAssetCd = $asset->rd_system_id ?? null;
                }
                break;

            case '8':
                $asset = DB::table('public.asset_road_details')->where('rd_system_id', $assetCd)->first();
                if ($asset) {
                    $assetName = $assetCd . ' - ' . ($asset->rd_name ?? null);
                    $parentAssetCd = null;
                }
                break;
            /*
            |--------------------------------------------------------------------------
            | 9 - PCI
            |--------------------------------------------------------------------------
            */
            case '9':

                $asset = DB::table('public.asset_road_pavement_condition_indexes')->where('pci_section_cd', $assetCd)->first();
                if ($asset) {
                    $assetName = $assetCd . ' - ' . ($asset->asset_name ?? null);
                    $parentAssetCd = $asset->rd_system_id ?? null;
                }
                break;
            case '11':
                $asset = DB::table('public.asset_road_distress_details')->where('rd_distress_cd', $assetCd)->first();
                if ($asset) {
                    $assetName = $assetCd . ' - ' . ($asset->distress_name ?? null);
                    $parentAssetCd = $asset->rd_system_id ?? null;
                }
                break;
            case '12':
                $asset = DB::table('public.asset_protection_wall_details')->where('protection_wall_cd', $assetCd)->first();
                if ($asset) {
                    $assetName = $assetCd . ' - ' . ($asset->wall_name ?? null);
                    $parentAssetCd = $asset->rd_system_id ?? null;
                }
                break;
        }
        if (empty($assetName)) {
            throw new \RuntimeException(
                "Unable to resolve maintainable asset. Asset Type: {$assetType}, Asset Code: {$assetCd}"
            );
        }
        return [
            'asset_name' => $assetName,
            'parent_asset_cd' => $parentAssetCd,
        ];
    }
    public function saveAssessment(Request $request)
    {
        $request->validate([
            'inspection_id' => ['required', 'integer'],
            'parameters' => ['required', 'array', 'min:1'],
            'parameters.*.parameter_id' => ['required', 'integer'],
            'parameters.*.parameter_set_id' => ['required', 'integer'],
            'parameters.*.parameter_code' => ['required', 'string'],
            'parameters.*.value' => ['required', 'numeric'],
            'parameters.*.maximum_range' => ['required', 'numeric', 'gt:0'],
            'parameters.*.weightage' => ['required', 'numeric', 'gte:0'],
        ]);
        $inspectionId = $request->input('inspection_id');
        $parameters = $request->input('parameters', []);
        try {
            DB::beginTransaction();
            $inspectionDetail = DB::table('maintenance.mtn_inspection_details')->where('id', $inspectionId)->first();
            $existingScore = DB::table('maintenance.mtn_inspection_score')->where('inspection_id', $inspectionId)->first();
            if ($existingScore) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Maintenance assessment for this inspection has already been completed.'
                ], 409);
            }
            $masResult = $this->mas_method($parameters);
            $mpsResult = $this->mps_method($parameters);
            $mas = $masResult['mas'];
            $conditionScore = $mpsResult['condition_score'];
            $criticalityScore = $mpsResult['criticality_score'];
            $mps = $mpsResult['mps'];
            $scoreId = DB::table('maintenance.mtn_inspection_score')
                ->insertGetId([
                    'inspection_id' => $inspectionId,
                    'mas_score' => $mas,
                    'condition_score' => $conditionScore,
                    'criticality_score' => $criticalityScore,
                    'mps_score' => $mps,
                    'condition_factor' => 0.6000,
                    'criticality_factor' => 0.4000,
                    'calculation_version' => '1.0',
                    'calculated_at' => now(),
                    'calculated_by' => auth()->id(),
                    'status' => 'ACTIVE',
                    'created_at' => now(),
                    'created_by' => auth()->id(),
                ]);
            foreach ($mpsResult['details'] as $detail) {
                $submittedParameter = collect($parameters)->firstWhere('parameter_code', $detail['parameter_code']);
                DB::table('maintenance.mtn_inspection_score_details')
                    ->insert([
                        'inspection_score_id' => $scoreId,
                        'parameter_set_id' => $submittedParameter['parameter_set_id'] ?? null,
                        'parameter_id' => $submittedParameter['parameter_id'] ?? null,
                        'parameter_code' => $detail['parameter_code'],
                        'parameter_name' => $submittedParameter['parameter_name'] ?? null,
                        'score_category' => $detail['category'],
                        'parameter_value' => $detail['value'],
                        'maximum_range' => $detail['maximum_range'],
                        'weightage' => $detail['weightage'],
                        'normalized_score' => $detail['normalized_score'],
                        'weighted_score' => $detail['weighted_score'],
                        'created_at' => now(),
                        'created_by' => auth()->id(),
                    ]);
            }
            $assetType = $inspectionDetail->insp_asset_type;
            $assetCd = $inspectionDetail->insp_asset_id;
            $assetInfo = $this->resolveMaintainableAsset($assetType, $assetCd);
            /*
            |--------------------------------------------------------------------------
            | Insert Maintainable Asset History Record
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            |
            | Same physical asset CAN appear multiple times.
            |
            | Example:
            |
            | RD001 / inspection 101
            | RD001 / inspection 205
            | RD001 / inspection 310
            |
            | Therefore there is NO unique constraint on:
            |
            | asset_type_cd + asset_cd
            |
            |--------------------------------------------------------------------------
            */
            $maintainableAssetId = DB::table('projects.prt_maintainable_assets')
                ->insertGetId([
                    'asset_name' => $assetInfo['asset_name'],
                    'asset_type_cd' => $assetType,
                    'asset_cd' => $assetCd,
                    'parent_asset_cd' => $assetInfo['parent_asset_cd'],
                    'flag' => 0,
                    'inspection_id' => $inspectionId,
                    'inspection_score_id' => $scoreId,
                    'created_at' => now(),
                    'created_by' => auth()->id(),
                ]);
            // ============================================================
            // Commit
            // ============================================================
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Maintenance Assessment saved successfully.',
                'score_id' => $scoreId,
                'mas' => $mas,
                'condition_score' => $conditionScore,
                'criticality_score' => $criticalityScore,
                'mps' => $mps,
            ]);
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error(
                'Maintenance Assessment save failed.',
                [
                    'inspection_id' => $inspectionId,
                    'exception' => $exception->getMessage(),
                ]
            );
            return response()->json([
                'success' => false,
                'message' => 'Unable to save Maintenance Assessment.'
            ], 500);
        }
    }

    // Saiful: 17-10-2026
    public function calculateAndSaveForAsset(string $inspectionCode): array
    {
        // Initialize variables for error logging.
        $assetCd = null;
        $assetTypeCd = null;
        try {
            return DB::connection('pgsql_maintenance')->transaction(function () use ($inspectionCode, &$assetCd, &$assetTypeCd) {
                /*
                |--------------------------------------------------------------------------
                | 1. Get the exact inspection using inspection code
                |--------------------------------------------------------------------------
                */
                $inspection = DB::table('maintenance.mtn_inspection_details as inspection')
                    ->leftJoin('public.asset_master_road_sub_assets as asset_type', 'asset_type.sub_asset_cd', '=', 'inspection.insp_asset_type')
                    ->select('inspection.*', 'asset_type.dept_cd as asset_dept_cd')
                    ->where('inspection.insp_cd', $inspectionCode)
                    ->first();

                if (!$inspection) {
                    throw new \RuntimeException("No inspection found for inspection code: {$inspectionCode}");
                }

                // Get asset information from this exact inspection.
                $inspectionId = $inspection->id;
                $assetCd = $inspection->insp_asset_id;
                $assetTypeCd = (string) $inspection->insp_asset_type;

                /*
                |--------------------------------------------------------------------------
                | 2. Prevent duplicate assessment for the same inspection
                |--------------------------------------------------------------------------
                */
                $existingScore = DB::table('maintenance.mtn_inspection_score')
                    ->where('inspection_id', $inspectionId)
                    ->where('status', 'ACTIVE')
                    ->exists();

                if ($existingScore) {
                    return [
                        'success' => false,
                        'message' => 'The latest inspection has already been assessed.',
                        'inspection_id' => $inspectionId,
                        'asset_cd' => $assetCd,
                        'asset_type_cd' => $assetTypeCd,
                    ];
                }

                /*
                |--------------------------------------------------------------------------
                | 3. Calculate total and average observation weight
                |--------------------------------------------------------------------------
                */
                $observations = DB::table('maintenance.mtn_insp_observation_details')
                    ->where('insp_id', $inspectionId)
                    ->get([
                        'obsrv_weightage',
                        'grading_cd',
                    ]);

                $totalWeight = $observations->sum(fn($item) => (float) $item->obsrv_weightage);

                $averageWeight = $observations->isNotEmpty() ? $totalWeight / $observations->count() : 0;
                $unsatisfactoryWeight = $observations
                    ->filter(
                        fn($item) =>
                        strtoupper((string) $item->grading_cd) === 'U'
                    )
                    ->sum(fn($item) => (float) $item->obsrv_weightage);

                /*
                |--------------------------------------------------------------------------
                | 4. Resolve the parent road for road sub-assets
                |--------------------------------------------------------------------------
                */
                $roadSystemId = null;

                switch ((string) $assetTypeCd) {
                    case '10': // Road
                    case '8':  // National Highway
                        $roadSystemId = $assetCd;
                        break;

                    case '0': // CD Work
                        $roadSystemId = DB::table('public.asset_road_cdwork_details')
                            ->where('rd_cdwork_cd', $assetCd)
                            ->value('rd_system_id');
                        break;

                    case '1': // Bridge
                        $roadSystemId = DB::table('public.asset_road_bridge_details')
                            ->where('rd_bridge_cd', $assetCd)
                            ->value('rd_system_id');
                        break;

                    case '2': // Pavement
                        $roadSystemId = DB::table('public.asset_road_pavement_details')
                            ->where('rd_pavement_cd', $assetCd)
                            ->value('rd_system_id');
                        break;

                    case '3': // Surface Type
                        $roadSystemId = DB::table('public.asset_road_surface_type_details')
                            ->where('rd_surface_cd', $assetCd)
                            ->value('rd_system_id');
                        break;

                    case '4': // Habitation
                        $roadSystemId = DB::table('public.asset_road_habitation_details')
                            ->where('habitation_cd', $assetCd)
                            ->value('rd_system_id');
                        break;

                    case '9': // PCI
                        $roadSystemId = DB::table('public.asset_road_pavement_condition_indexes')
                            ->where('pci_section_cd', $assetCd)
                            ->value('rd_system_id');
                        break;

                    case '11': // Distress
                        $roadSystemId = DB::table('public.asset_road_distress_details')
                            ->where('rd_distress_cd', $assetCd)
                            ->value('rd_system_id');
                        break;

                    case '12': // Protection Wall
                        $roadSystemId = DB::table('public.asset_protection_wall_details')
                            ->where('protection_wall_cd', $assetCd)
                            ->value('rd_system_id');
                        break;
                }

                /*
                |--------------------------------------------------------------------------
                | 5. Get average habitation score for the parent road
                |--------------------------------------------------------------------------
                */
                $habitationScore = 0;

                if (!empty($roadSystemId)) {
                    $habitationScore = (float) (
                        DB::table('public.asset_road_habitation_details')
                        ->where('rd_system_id', $roadSystemId)
                        ->avg('habitation_score') ?? 0
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 6. Get PCI value
                |--------------------------------------------------------------------------
                */
                $pciValue = 0;

                if (!empty($inspection->pci_section_cd)) {
                    $pciValue = (float) (
                        DB::table('public.asset_road_pavement_condition_indexes')
                        ->where('pci_section_cd', $inspection->pci_section_cd)
                        ->value('pci_value') ?? 0
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 7. Get asset criticality index
                |--------------------------------------------------------------------------
                */
                $criticalityIndex = (float) (
                    DB::table('maintenance.master_asset_criticality_indexes')
                    ->where('asset_type', $assetTypeCd)
                    ->where('asset_id', $assetCd)
                    ->orderByDesc('updated_at')
                    ->orderByDesc('id')
                    ->value('criticality_index') ?? 0
                );

                /*
                |--------------------------------------------------------------------------
                | 8. Get road category score from the parent road
                |--------------------------------------------------------------------------
                */
                $roadCategoryScore = 0;

                if (!empty($roadSystemId)) {
                    $roadCategoryScore = (float) (
                        DB::table('public.asset_road_details as road')
                        ->leftJoin('public.asset_master_road_category as category', 'category.rd_catg_cd', '=', 'road.rd_category_cd')
                        ->where('road.rd_system_id', $roadSystemId)
                        ->value('category.maintenance_score') ?? 0
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 9. Load dynamic maintenance parameters from the database
                |--------------------------------------------------------------------------
                */
                $parameterRows = DB::table('maintenance.master_set_of_mtn_parameters_dept_wise as parameter_set')
                    ->join('maintenance.master_mtn_parameter_master as parameter', 'parameter.id', '=', 'parameter_set.parameter_id')
                    ->leftJoin('maintenance.master_mtn_parameter_range as parameter_range', 'parameter_range.parameter_id', '=', 'parameter.id')
                    ->leftJoin(
                        'maintenance.master_each_mtn_parameters_weightage as parameter_weightage',
                        function ($join) {
                            $join->on('parameter_weightage.mtn_parameter_set_id', '=', 'parameter_set.id')
                                ->on('parameter_weightage.parameter_id', '=', 'parameter.id');
                        }
                    )
                    ->where('parameter_set.dept_cd', $inspection->asset_dept_cd)
                    ->where('parameter_set.asset_type_cd', $assetTypeCd)
                    ->where('parameter.status', 'ACTIVE')
                    ->where(function ($query) {
                        $query->where('parameter_range.status', 'ACTIVE')
                            ->orWhereNull('parameter_range.status');
                    })
                    ->select([
                        'parameter_set.id as parameter_set_id',
                        'parameter.id as parameter_id',
                        'parameter.parameter_code',
                        'parameter.parameter_name',
                        'parameter_range.upper_range',
                        'parameter_weightage.weightage',
                    ])
                    ->orderBy('parameter_set.id')
                    ->get();

                if ($parameterRows->isEmpty()) {
                    throw new \RuntimeException(
                        "No active maintenance parameters configured for asset type {$assetTypeCd}."
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | 10. Map database values to configured parameter codes
                |--------------------------------------------------------------------------
                */
                $parameterValues = [
                    'FIELD_OBSERVATION_WEIGHTAGE' => $averageWeight,
                    'PCI' => $pciValue,
                    'HABITATION_INDEX' => $habitationScore,
                    'CRITICALITY_INDEX' => $criticalityIndex,
                    'ROAD_CONNECTIVITY' => 0,
                    'ROAD_CATEGORY' => $roadCategoryScore,
                    'PRIORITY_INDEX' => 0,
                ];

                $parameters = [];

                foreach ($parameterRows as $row) {
                    $parameters[] = [
                        'parameter_id' => $row->parameter_id,
                        'parameter_set_id' => $row->parameter_set_id,
                        'parameter_code' => $row->parameter_code,
                        'parameter_name' => $row->parameter_name,
                        'value' => (float) ($parameterValues[$row->parameter_code] ?? 0),
                        'maximum_range' => (float) ($row->upper_range ?? 0),
                        'weightage' => (float) ($row->weightage ?? 0),
                    ];
                }

                /*
                |--------------------------------------------------------------------------
                | 11. Calculate MAS and MPS using existing methods
                |--------------------------------------------------------------------------
                */
                $masResult = $this->mas_method($parameters);
                $mpsResult = $this->mps_method($parameters);

                $mas = $masResult['mas'];
                $conditionScore = $mpsResult['condition_score'];
                $criticalityScore = $mpsResult['criticality_score'];
                $mps = $mpsResult['mps'];

                /*
                |--------------------------------------------------------------------------
                | 12. Save score header
                |--------------------------------------------------------------------------
                */
                $now = now();
                $userId = auth()->id() ?? ($inspection->created_by ?? null);

                $scoreId = DB::table('maintenance.mtn_inspection_score')
                    ->insertGetId([
                        'inspection_id' => $inspectionId,
                        'mas_score' => $mas,
                        'condition_score' => $conditionScore,
                        'criticality_score' => $criticalityScore,
                        'mps_score' => $mps,
                        'condition_factor' => 0.6000,
                        'criticality_factor' => 0.4000,
                        'calculation_version' => '1.0',
                        'calculated_at' => $now,
                        'calculated_by' => $userId,
                        'status' => 'ACTIVE',
                        'created_at' => $now,
                        'created_by' => $userId,
                    ]);

                /*
                |--------------------------------------------------------------------------
                | 13. Save parameter-level score details
                |--------------------------------------------------------------------------
                */
                foreach ($mpsResult['details'] as $detail) {
                    $submittedParameter = collect($parameters)->firstWhere('parameter_code', $detail['parameter_code']);
                    DB::table('maintenance.mtn_inspection_score_details')
                        ->insert([
                            'inspection_score_id' => $scoreId,
                            'parameter_set_id' => $submittedParameter['parameter_set_id'] ?? null,
                            'parameter_id' => $submittedParameter['parameter_id'] ?? null,
                            'parameter_code' => $detail['parameter_code'],
                            'parameter_name' => $submittedParameter['parameter_name'] ?? null,
                            'score_category' => $detail['category'],
                            'parameter_value' => $detail['value'],
                            'maximum_range' => $detail['maximum_range'],
                            'weightage' => $detail['weightage'],
                            'normalized_score' => $detail['normalized_score'],
                            'weighted_score' => $detail['weighted_score'],
                            'created_at' => $now,
                            'created_by' => $userId,
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | 14. Save maintainable asset history
                |--------------------------------------------------------------------------
                */
                $assetInfo = $this->resolveMaintainableAsset($assetTypeCd, $assetCd);
                $maintainableAssetId = DB::table('projects.prt_maintainable_assets')->insertGetId([
                    'asset_name' => $assetInfo['asset_name'],
                    'asset_type_cd' => $assetTypeCd,
                    'asset_cd' => $assetCd,
                    'parent_asset_cd' => $assetInfo['parent_asset_cd'],
                    'flag' => 0,
                    'inspection_id' => $inspectionId,
                    'inspection_score_id' => $scoreId,
                    'created_at' => $now,
                    'created_by' => $userId,
                ]);

                return [
                    'success' => true,
                    'message' => 'Maintenance assessment calculated and saved successfully.',
                    'asset_cd' => $assetCd,
                    'asset_type_cd' => $assetTypeCd,
                    'inspection_id' => $inspectionId,
                    'inspection_score_id' => $scoreId,
                    'maintainable_asset_id' => $maintainableAssetId,
                    'mas' => $mas,
                    'condition_score' => $conditionScore,
                    'criticality_score' => $criticalityScore,
                    'mps' => $mps,
                    'average_habitation_score' => round($habitationScore, 2),
                    'total_observation_weight' => $totalWeight,
                    'average_observation_weight' => round($averageWeight, 2),
                ];
            });
        } catch (\Throwable $exception) {
            Log::error('Generic maintenance assessment failed.', [
                'asset_cd' => $assetCd,
                'asset_type_cd' => $assetTypeCd,
                'exception' => $exception->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'Unable to calculate and save maintenance assessment.',
                'error' => $exception->getMessage(),
            ];
        }
    }
}
