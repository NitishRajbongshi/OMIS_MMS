<?php

namespace App\Http\Controllers\FinalizeRoadsAndBridges;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssetMasterDeckType;
use App\Models\AssetMasterPileType;
use App\Models\AssetMasterWellType;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetMasterBridgeType;
use App\Models\AssetMasterBearingType;
use App\Models\AssetMasterAbutmentType;
use App\Models\AssetMasterHandrailType;
use App\Models\AssetMasterRoadCondition;
use App\Models\AssetMasterExpansionJoint;
use App\Models\AssetMasterFoundationType;
use App\Models\AssetMasterConstructionType;
use App\Models\AssetMasterSuperStructureType;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class FinalizeBridgeController extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
        Log::info("Finalize Bridge Controller");
        $this->middleware("auth");
    }
    public function index()
    {
        $roadID = session('system_id');
        try {
            if ($roadID != null) {
                $user = Auth::user();
                $bridgeTypes = AssetMasterBridgeType::all();
                $constructionTypes = AssetMasterConstructionType::all();
                $foundationTypes = AssetMasterFoundationType::all();
                $abutmentTypes = AssetMasterAbutmentType::all();
                $superStructureType = AssetMasterSuperStructureType::all();
                $handrailTypes = AssetMasterHandrailType::all();
                $deckTypes = AssetMasterDeckType::all();
                $expJoints = AssetMasterExpansionJoint::all();
                $bridgeConditions = AssetMasterRoadCondition::all();
                $bearingTypes = AssetMasterBearingType::all();
                $pileTypes = AssetMasterPileType::all();
                $wellTypes = AssetMasterWellType::all();
                // In case of switching between the offices
                // start
                $office_cd = $user->office;
                $users_office_type_cd = $user->office_type_cd;
                $zone_cd = null;
                $circle_cd = null;
                $division_cd = null;
                $sub_division_cd = null;
                $userMapping = DB::table('asset_user_mappings')
                    ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd')
                    ->where('user_id', '=', $user->id)
                    ->get()->first();
                $zone_cd = $userMapping->zone_cd;
                $circle_cd = $userMapping->circle_cd;
                $division_cd = $userMapping->division_cd;
                $sub_division_cd = $userMapping->sub_division_cd;
                if (session('office_charge_type') == 1) {
                    $office_cd = session('office_cd');
                    $users_office_type_cd = session('users_office_type_cd');
                    $officeDivisionDtls = DB::table('office_details as ofd')
                        ->select('ofd.zone_cd', 'ofd.circle_cd', 'ofd.division_cd', 'ofd.sub_division_cd')
                        ->where('ofd.id', '=', $office_cd)
                        ->get()->first();
                    if ($officeDivisionDtls) {
                        $zone_cd = $officeDivisionDtls->zone_cd;
                        $circle_cd = $officeDivisionDtls->circle_cd;
                        $division_cd = $officeDivisionDtls->division_cd;
                        $sub_division_cd = $officeDivisionDtls->sub_division_cd;
                    }
                }
                // End
                $draftBridgeBaseQuery = DB::table('asset_road_bridge_details_draft')
                    ->select(
                        'asset_road_bridge_details_draft.*',
                        'asset_master_bridge_type.bridge_type_descr',
                        'asset_master_construction_types.construction_type_descr',
                        // 'asset_master_foundation_types.foundation_descr',
                        'asset_master_abutment_types.abutment_type_descr',
                        'asset_master_super_structure_types.st_type_descr',
                        'asset_master_handrail_types.hand_rail_type_descr',
                        'asset_master_deck_types.deck_type_descr',
                        // 'asset_master_bearing_types.bearing_type_descr',
                        'asset_master_expansion_joints.expn_joint_descr',
                        'asset_master_road_condition.rd_condition_descr',
                        // 'asset_master_pile_types.pile_type_descr',
                        // 'asset_master_well_types.well_type_descr'
                        //saiful # 29-04-2026 # Start
                        'pp.project_cd as project_cd'
                        //saiful # 29-04-2026 # End
                    )
                    ->leftJoin('asset_master_bridge_type', 'asset_road_bridge_details_draft.bridge_type_cd', '=', 'asset_master_bridge_type.bridge_type_cd')
                    ->leftJoin('asset_master_construction_types', 'asset_road_bridge_details_draft.construction_type_cd', '=', 'asset_master_construction_types.construction_type_cd')
                    // ->leftJoin('asset_master_foundation_types', 'asset_road_bridge_details_draft.foundation_type_cd', '=', 'asset_master_foundation_types.foundation_cd')
                    ->leftJoin('asset_master_abutment_types', 'asset_road_bridge_details_draft.abutment_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
                    ->leftJoin('asset_master_super_structure_types', 'asset_road_bridge_details_draft.super_structure_type_cd', '=', 'asset_master_super_structure_types.st_type_cd')
                    ->leftJoin('asset_master_handrail_types', 'asset_road_bridge_details_draft.handrail_type_cd', '=', 'asset_master_handrail_types.hand_rail_type_cd')
                    ->leftJoin('asset_master_deck_types', 'asset_road_bridge_details_draft.deck_type_cd', '=', 'asset_master_deck_types.deck_type_cd')
                    // ->leftJoin('asset_master_bearing_types', 'asset_road_bridge_details_draft.bearings', '=', 'asset_master_bearing_types.bearing_type_cd')
                    ->leftJoin('asset_master_expansion_joints', 'asset_road_bridge_details_draft.expansion_join_cd', '=', 'asset_master_expansion_joints.expn_joint_cd')
                    ->leftJoin('asset_master_road_condition', 'asset_road_bridge_details_draft.bridge_condition', '=', 'asset_master_road_condition.rd_condition_cd')
                    // ->leftJoin('asset_master_pile_types', 'asset_road_bridge_details_draft.pile_type', '=', 'asset_master_pile_types.pile_type_cd')
                    // ->leftJoin('asset_master_well_types', 'asset_road_bridge_details_draft.well_type', '=', 'asset_master_well_types.well_type_cd')
                    //saiful # 29-04-2026 # Start
                    ->leftJoin('prt_project_asset_plan as pp', 'asset_road_bridge_details_draft.asset_plan_id', '=', 'pp.id')
                    //saiful # 29-04-2026 # End
                    ->where('asset_road_bridge_details_draft.rd_system_id', '=', $roadID)
                    ->where('sent_for_finalize', '=', 'Y')
                    ->orderBy('updated_at', 'desc');

                if (Auth::user()->office_type_cd == 'ZO') {
                    $zoneCd = session('userMapping')->zone_cd;
                    $draftBridgeBaseQuery->whereIn('asset_road_bridge_details_draft.created_at_office_cd', function ($query) use ($zoneCd) {
                        $query->select(DB::raw('distinct(aum.office_cd)'))
                            ->from('asset_user_mappings as aum')
                            ->where('aum.zone_cd', $zoneCd);
                    });
                }
                if (Auth::user()->office_type_cd == 'CO') {
                    $circleCd = session('userMapping')->circle_cd;
                    $draftBridgeBaseQuery->whereIn('asset_road_bridge_details_draft.created_at_office_cd', function ($query) use ($circleCd) {
                        $query->select(DB::raw('distinct(aum.office_cd)'))
                            ->from('asset_user_mappings as aum')
                            ->where('aum.circle_cd', $circleCd);
                    });
                }
                if (Auth::user()->office_type_cd == 'DO') {
                    $divisionCd = session('userMapping')->division_cd;
                    $draftBridgeBaseQuery->whereIn('asset_road_bridge_details_draft.created_at_office_cd', function ($query) use ($divisionCd) {
                        $query->select(DB::raw('distinct(aum.office_cd)'))
                            ->from('asset_user_mappings as aum')
                            ->where('aum.division_cd', $divisionCd);
                    });
                }
                if (Auth::user()->office_type_cd == 'SDO') {
                    $subDivisionCd = session('userMapping')->sub_division_cd;
                    $draftBridgeBaseQuery->whereIn('asset_road_bridge_details_draft.created_at_office_cd', function ($query) use ($subDivisionCd) {
                        $query->select(DB::raw('distinct(aum.office_cd)'))
                            ->from('asset_user_mappings as aum')
                            ->where('aum.sub_division_cd', $subDivisionCd);
                    });
                }

                $bridgeDetails = $draftBridgeBaseQuery->get();
                $constructionTypes = AssetMasterConstructionType::all();

                return view('road.finalize.bridge', compact(
                    'roadID',
                    'user',
                    'bridgeDetails',
                    'constructionTypes',
                    'bridgeTypes',
                    'constructionTypes',
                    'foundationTypes',
                    'abutmentTypes',
                    'superStructureType',
                    'handrailTypes',
                    'deckTypes',
                    'expJoints',
                    'bridgeConditions',
                    'bearingTypes',
                    'pileTypes',
                    'wellTypes'
                ));
            } else {
                Log::error("Road not found!");
            }
            $query = DB::getQueryLog();
            Log::info($query);
        } catch (QueryException $e) {
            Log::error("Database Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'query' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);
            return response()->view('errors.generic', [], 500);
        } catch (Exception $e) {
            // Handles general errors
            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->view('errors.generic', [], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            if (isset($request->_token)) {
                $roadID = session('roadId');
                $createdBy = Auth::user()->id;
                $currentTime = now();

                // Copy data from student_table_draft to another_table
                $status = DB::table('asset_road_bridge_details')->insertUsing([
                    'rd_bridge_cd',
                    'rd_system_id',
                    'bridge_type_cd',
                    'bridge_name',
                    'chainage',
                    'bridge_lane',
                    'river_name',
                    'cd_bridge_length',
                    'construction_type_cd',
                    'year_of_construction',
                    'no_of_span',
                    'span_length',
                    'kerb_height',
                    'foundation_type_cd',
                    'year_of_rehabilitation',
                    'no_of_piers',
                    'pier_size',
                    'abutment_type_cd',
                    'handrail_type_cd',
                    'deck_type_cd',
                    'carriage_width',
                    'guard_stone',
                    'load_capacity',
                    'signs',
                    'lowest_water_level',
                    'highest_flood_level',
                    'rfl',
                    'source_depth',
                    'discharge',
                    'deck_level',
                    'super_structure_type_cd',
                    'footh_path',
                    'bearings',
                    'expansion_join_cd',
                    'bridge_condition',
                    'next_schedule_inspection_date',
                    'created_at_office_cd',
                    'bridge_number',
                    'bridge_location',
                    'date_of_last_inspection',
                    'kerb_width',
                    'minimum_water_level',
                    'bridge_remark',
                    'pile_diameter',
                    'pile_length',
                    'pile_type',
                    'well_type',
                    'well_size',
                    'open_foundation_size',
                    'depth_open_foundation_size',
                    'bridge_width',
                    'has_abutment_wall',
                    'has_wing_wall',
                    'has_head_wall',
                    'has_retain_wall',
                    'has_safety_apron',
                    'safety_apron_type',
                    'safety_apron_hand_rail_type',
                    'apron_width',
                    'created_at',
                    'updated_at',
                    'updated_by',
                    'created_by',
                    //saiful # 29-04-2026 # Start
                    'asset_plan_id'
                    //saiful # 29-04-2026 # End
                ], function ($query) use ($roadID, $currentTime, $createdBy) {
                    $query->from('asset_road_bridge_details_draft')
                        ->where('rd_system_id', '=', $roadID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'rd_bridge_cd',
                            'rd_system_id',
                            'bridge_type_cd',
                            'bridge_name',
                            'chainage',
                            'bridge_lane',
                            'river_name',
                            'cd_bridge_length',
                            'construction_type_cd',
                            'year_of_construction',
                            'no_of_span',
                            'span_length',
                            'kerb_height',
                            'foundation_type_cd',
                            'year_of_rehabilitation',
                            'no_of_piers',
                            'pier_size',
                            'abutment_type_cd',
                            'handrail_type_cd',
                            'deck_type_cd',
                            'carriage_width',
                            'guard_stone',
                            'load_capacity',
                            'signs',
                            'lowest_water_level',
                            'highest_flood_level',
                            'rfl',
                            'source_depth',
                            'discharge',
                            'deck_level',
                            'super_structure_type_cd',
                            'footh_path',
                            'bearings',
                            'expansion_join_cd',
                            'bridge_condition',
                            'next_schedule_inspection_date',
                            'created_at_office_cd',
                            'bridge_number',
                            'bridge_location',
                            'date_of_last_inspection',
                            'kerb_width',
                            'minimum_water_level',
                            'bridge_remark',
                            'pile_diameter',
                            'pile_length',
                            'pile_type',
                            'well_type',
                            'well_size',
                            'open_foundation_size',
                            'depth_open_foundation_size',
                            'bridge_width',
                            'has_abutment_wall',
                            'has_wing_wall',
                            'has_head_wall',
                            'has_retain_wall',
                            'has_safety_apron',
                            'safety_apron_type',
                            'safety_apron_hand_rail_type',
                            'apron_width',
                            DB::raw("'$currentTime' as created_at"),
                            DB::raw("'$currentTime' as updated_at"),
                            DB::raw("'$createdBy' as updated_by"),
                            DB::raw("'$createdBy' as created_by"),
                            //saiful # 29-04-2026 # Start
                            'asset_plan_id'
                            //saiful # 29-04-2026 # End
                        );
                });

                if ($status > 0) {
                    DB::table('asset_road_bridge_details_draft')
                        ->where('rd_system_id', '=', $roadID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->delete();

                    return response()->json([
                        'status' => 200,
                        'message' => 'Finalize all data successfully!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Data not available to finalize!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unothorized Access'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }

    public function acceptSingleBridgeData(Request $request)
    {
        if (!$request->header('X-CSRF-TOKEN')) {
            return response()->json([
                'status' => 401,
                'message' => 'Unauthorized Access'
            ], 401);
        }
        DB::beginTransaction();
        try {
            $bridgeID = $request->id;
            $approvedBy = Auth::user()->id;
            $currentTime = now();
            $this->finalizeWingWallData($bridgeID);
            $this->finalizeHeadWallData($bridgeID);
            $this->finalizeRetainWallData($bridgeID);
            $this->finalizeSpanWallData($bridgeID);
            $this->finalizePierData($bridgeID);
            $this->finalizeAbutmentData($bridgeID);

            //saiful # 29-04-2026 # Start
            //check if same bridge code exist in main table or not, if exist then update otherwise insert
            $existingBridge = DB::table('asset_road_bridge_details')
                ->where('rd_bridge_cd', $bridgeID)
                ->first();
            if ($existingBridge) {
                return $this->handleApprovalOfExistingBridge($bridgeID, $approvedBy, $currentTime);
            } else {
                //saiful # 29-04-2026 # End
                $status = DB::table('asset_road_bridge_details')->insertUsing([
                    'rd_bridge_cd',
                    'rd_system_id',
                    'bridge_type_cd',
                    'bridge_name',
                    'chainage',
                    'bridge_lane',
                    'river_name',
                    'cd_bridge_length',
                    'construction_type_cd',
                    'year_of_construction',
                    'no_of_span',
                    'span_length',
                    'kerb_distance',
                    'year_of_rehabilitation',
                    'no_of_piers',
                    'abutment_type_cd',
                    'super_structure_type_cd',
                    'handrail_type_cd',
                    'deck_type_cd',
                    'carriage_width',
                    'guard_stone',
                    'load_capacity',
                    'signs',
                    'lowest_water_level',
                    'highest_flood_level',
                    'rfl',
                    'source_depth',
                    'discharge',
                    'deck_level',
                    'footh_path',
                    'expansion_join_cd',
                    'bridge_condition',
                    'next_schedule_inspection_date',
                    'created_at',
                    'updated_at',
                    'updated_by',
                    'created_by',
                    'created_at_office_cd',
                    'bridge_number',
                    'bridge_location',
                    'date_of_last_inspection',
                    'kerb_width',
                    'minimum_water_level',
                    'bridge_remark',
                    'bridge_width',
                    'has_head_wall',
                    'has_wing_wall',
                    'has_retain_wall',
                    'has_abutment_wall',
                    'has_safety_apron',
                    'safety_apron_type',
                    'safety_apron_hand_rail_type',
                    'apron_width',
                    'approved_by',
                    'approved_at',
                    //saiful # 29-04-2026 # Start
                    'asset_plan_id'
                    //saiful # 29-04-2026 # End
                ], function ($query) use ($bridgeID, $currentTime, $approvedBy) {
                    $query->from('asset_road_bridge_details_draft')
                        ->where('rd_bridge_cd', '=', $bridgeID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'rd_bridge_cd',
                            'rd_system_id',
                            'bridge_type_cd',
                            'bridge_name',
                            'chainage',
                            'bridge_lane',
                            'river_name',
                            'cd_bridge_length',
                            'construction_type_cd',
                            'year_of_construction',
                            'no_of_span',
                            'span_length',
                            'kerb_height',
                            'year_of_rehabilitation',
                            'no_of_piers',
                            'abutment_type_cd',
                            'super_structure_type_cd',
                            'handrail_type_cd',
                            'deck_type_cd',
                            'carriage_width',
                            'guard_stone',
                            'load_capacity',
                            'signs',
                            'lowest_water_level',
                            'highest_flood_level',
                            'rfl',
                            'source_depth',
                            'discharge',
                            'deck_level',
                            'footh_path',
                            'expansion_join_cd',
                            'bridge_condition',
                            'next_schedule_inspection_date',
                            'created_at',
                            'updated_at',
                            'updated_by',
                            'created_by',
                            'created_at_office_cd',
                            'bridge_number',
                            'bridge_location',
                            'date_of_last_inspection',
                            'kerb_width',
                            'minimum_water_level',
                            'bridge_remark',
                            'bridge_width',
                            'has_head_wall',
                            'has_wing_wall',
                            'has_retain_wall',
                            'has_abutment_wall',
                            'has_safety_apron',
                            'safety_apron_type',
                            'safety_apron_hand_rail_type',
                            'apron_width',
                            DB::raw("'$approvedBy' as approved_by"),
                            DB::raw("'$currentTime' as approved_at"),
                            //saiful # 29-04-2026 # Start
                            'asset_plan_id'
                            //saiful # 29-04-2026 # End
                        );
                });
                if ($status > 0) {
                    DB::table('asset_road_bridge_details_draft')
                        ->where('rd_bridge_cd', '=', $bridgeID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->delete();
                    DB::commit();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Finalize data successfully!'
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'No data available to finalize.'
                    ], 204);
                }
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Bridge finalization failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ], 500);
        }
    }

    //saiful # 29-04-2026 # Start
    private function handleApprovalOfExistingBridge($bridgeID, $approvedBy, $currentTime)
    {
        Log::info("Bridge with ID $bridgeID already exists. Updating existing record.");
        $bridgeDraftInfos = DB::table('asset_road_bridge_details_draft')
            ->where('rd_bridge_cd', '=', $bridgeID)
            ->where('sent_for_finalize', '=', 'Y')
            ->first();
        $status = DB::table('asset_road_bridge_details_hist')->insertUsing([
            'rd_bridge_cd',
            'rd_system_id',
            'bridge_type_cd',
            'bridge_name',
            'chainage',
            'bridge_lane',
            'river_name',
            'cd_bridge_length',
            'construction_type_cd',
            'year_of_construction',
            'no_of_span',
            'span_length',
            'kerb_distance',
            'year_of_rehabilitation',
            'no_of_piers',
            'abutment_type_cd',
            'super_structure_type_cd',
            'handrail_type_cd',
            'deck_type_cd',
            'carriage_width',
            'guard_stone',
            'load_capacity',
            'signs',
            'lowest_water_level',
            'highest_flood_level',
            'rfl',
            'source_depth',
            'discharge',
            'deck_level',
            'footh_path',
            'expansion_join_cd',
            'bridge_condition',
            'next_schedule_inspection_date',
            'created_at',
            'updated_at',
            'updated_by',
            'created_by',
            'created_at_office_cd',
            'bridge_number',
            'bridge_location',
            'date_of_last_inspection',
            'kerb_width',
            'minimum_water_level',
            'bridge_remark',
            'bridge_width',
            'has_head_wall',
            'has_wing_wall',
            'has_retain_wall',
            'has_abutment_wall',
            'has_safety_apron',
            'safety_apron_type',
            'safety_apron_hand_rail_type',
            'apron_width',
            'approved_by',
            'approved_at',
            'asset_plan_id',
            'hist_created_by',
            'hist_created_on',
            'hist_remarks'
        ], function ($query) use ($bridgeID, $currentTime, $approvedBy) {
            $query->from('asset_road_bridge_details')
                ->where('rd_bridge_cd', '=', $bridgeID)
                ->select(
                    'rd_bridge_cd',
                    'rd_system_id',
                    'bridge_type_cd',
                    'bridge_name',
                    'chainage',
                    'bridge_lane',
                    'river_name',
                    'cd_bridge_length',
                    'construction_type_cd',
                    'year_of_construction',
                    'no_of_span',
                    'span_length',
                    'kerb_height',
                    'year_of_rehabilitation',
                    'no_of_piers',
                    'abutment_type_cd',
                    'super_structure_type_cd',
                    'handrail_type_cd',
                    'deck_type_cd',
                    'carriage_width',
                    'guard_stone',
                    'load_capacity',
                    'signs',
                    'lowest_water_level',
                    'highest_flood_level',
                    'rfl',
                    'source_depth',
                    'discharge',
                    'deck_level',
                    'footh_path',
                    'expansion_join_cd',
                    'bridge_condition',
                    'next_schedule_inspection_date',
                    'created_at',
                    'updated_at',
                    'updated_by',
                    'created_by',
                    'created_at_office_cd',
                    'bridge_number',
                    'bridge_location',
                    'date_of_last_inspection',
                    'kerb_width',
                    'minimum_water_level',
                    'bridge_remark',
                    'bridge_width',
                    'has_head_wall',
                    'has_wing_wall',
                    'has_retain_wall',
                    'has_abutment_wall',
                    'has_safety_apron',
                    'safety_apron_type',
                    'safety_apron_hand_rail_type',
                    'apron_width',
                    'approved_by',
                    'approved_at',
                    'asset_plan_id',
                    DB::raw("'$approvedBy' as hist_created_by"),
                    DB::raw("'$currentTime' as hist_created_on"),
                    DB::raw("'Asset Redefined by Project' as hist_remarks"),
                );
        });
        if ($status > 0) {
            $status = DB::table('public.asset_road_bridge_details')
                ->where('rd_bridge_cd', '=', $bridgeID)
                ->update([
                    'bridge_type_cd' => $bridgeDraftInfos->bridge_type_cd,
                    'bridge_name' => $bridgeDraftInfos->bridge_name,
                    'chainage' => $bridgeDraftInfos->chainage,
                    'bridge_lane' => $bridgeDraftInfos->bridge_lane,
                    'river_name' => $bridgeDraftInfos->river_name,
                    'cd_bridge_length' => $bridgeDraftInfos->cd_bridge_length,
                    'construction_type_cd' => $bridgeDraftInfos->construction_type_cd,
                    'year_of_construction' => $bridgeDraftInfos->year_of_construction,
                    'no_of_span' => $bridgeDraftInfos->no_of_span,
                    'span_length' => $bridgeDraftInfos->span_length,
                    'kerb_height' => $bridgeDraftInfos->kerb_height,
                    'year_of_rehabilitation' => $bridgeDraftInfos->year_of_rehabilitation,
                    'no_of_piers' => $bridgeDraftInfos->no_of_piers,
                    'abutment_type_cd' => $bridgeDraftInfos->abutment_type_cd,
                    'super_structure_type_cd' => $bridgeDraftInfos->super_structure_type_cd,
                    'handrail_type_cd' => $bridgeDraftInfos->handrail_type_cd,
                    'deck_type_cd' => $bridgeDraftInfos->deck_type_cd,
                    'carriage_width' => $bridgeDraftInfos->carriage_width,
                    'guard_stone' => $bridgeDraftInfos->guard_stone,
                    'load_capacity' => $bridgeDraftInfos->load_capacity,
                    'signs' => $bridgeDraftInfos->signs,
                    'lowest_water_level' => $bridgeDraftInfos->lowest_water_level,
                    'highest_flood_level' => $bridgeDraftInfos->highest_flood_level,
                    'rfl' => $bridgeDraftInfos->rfl,
                    'source_depth' => $bridgeDraftInfos->source_depth,
                    'discharge' => $bridgeDraftInfos->discharge,
                    'deck_level' => $bridgeDraftInfos->deck_level,
                    'footh_path' => $bridgeDraftInfos->footh_path,
                    'expansion_join_cd' => $bridgeDraftInfos->expansion_join_cd,
                    'bridge_condition' => $bridgeDraftInfos->bridge_condition,
                    'next_schedule_inspection_date' => $bridgeDraftInfos->next_schedule_inspection_date,
                    'bridge_number' => $bridgeDraftInfos->bridge_number,
                    'bridge_location' => $bridgeDraftInfos->bridge_location,
                    'date_of_last_inspection' => $bridgeDraftInfos->date_of_last_inspection,
                    'kerb_width' => $bridgeDraftInfos->kerb_width,
                    'minimum_water_level' => $bridgeDraftInfos->minimum_water_level,
                    'bridge_remark' => $bridgeDraftInfos->bridge_remark,
                    'bridge_width' => $bridgeDraftInfos->bridge_width,
                    'has_head_wall' => $bridgeDraftInfos->has_head_wall,
                    'has_wing_wall' => $bridgeDraftInfos->has_wing_wall,
                    'has_retain_wall' => $bridgeDraftInfos->has_retain_wall,
                    'has_abutment_wall' => $bridgeDraftInfos->has_abutment_wall,
                    'has_safety_apron' => $bridgeDraftInfos->has_safety_apron,
                    'safety_apron_type' => $bridgeDraftInfos->safety_apron_type,
                    'safety_apron_hand_rail_type' => $bridgeDraftInfos->safety_apron_hand_rail_type,
                    'apron_width' => $bridgeDraftInfos->apron_width,
                    'asset_plan_id' => $bridgeDraftInfos->asset_plan_id,
                    'updated_at' => now(),
                    'updated_by' => auth()->id(),
                    'approved_by' => $approvedBy,
                    'approved_at' => $currentTime

                ]);
            DB::table('asset_road_bridge_details_draft')
                ->where('rd_bridge_cd', '=', $bridgeID)
                ->where('sent_for_finalize', '=', 'Y')
                ->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Finalize data successfully!'
            ], 200);
        } else {
            return response()->json([
                'status' => 204,
                'message' => 'No data available to finalize.'
            ], 204);
        }
    }
    //saiful # 29-04-2026 # End
    public function rejectSingleBridgeData(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $bridgeID = $request->id;
                $status = DB::table('asset_road_bridge_details_draft')
                    ->where('sent_for_finalize', 'Y')
                    ->where('rd_bridge_cd', $bridgeID)
                    ->update([
                        'is_rejected' => 'Y',
                        'reason_of_rejection' => $request->reason,
                        'date_of_rejection' => Carbon::now(),
                        'rejected_by' => Auth::user()->id,
                        'sent_for_finalize' => 'N',
                        'updated_at' => Carbon::now()
                    ]);

                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Bridge data rejected!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Data not available to Reject!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unothorized Access'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }

    public function finalizeWingWallData($bridge_id)
    {
        try {
            DB::table('asset_road_bridge_wing_wall_details')->insertUsing([
                'wing_wall_sr_no',
                'rd_bridge_cd',
                'top_width',
                'bottom_width',
                'height1',
                'height2',
                'created_by',
                'created_at',
                'updated_at',
                'wing_wall_type_cd',
                'thickness',
                'slope',
                'transitions',
                'angle',
                'radius',
                'length',
            ], function ($query) use ($bridge_id) {
                $query->from('asset_road_bridge_wing_wall_draft_details')
                    ->where('rd_bridge_cd', $bridge_id)
                    ->select(
                        'wing_wall_sr_no',
                        'rd_bridge_cd',
                        'top_width',
                        'bottom_width',
                        'height1',
                        'height2',
                        'created_by',
                        'created_at',
                        'updated_at',
                        'wing_wall_type_cd',
                        'thickness',
                        'slope',
                        'transitions',
                        'angle',
                        'radius',
                        'length',
                    );
            });

            DB::table('asset_road_bridge_wing_wall_draft_details')
                ->where('rd_bridge_cd', $bridge_id)
                ->delete();
        } catch (\Throwable $e) {
            Log::error('Failed to finalize wing wall data', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'bridge_id' => $bridge_id
            ]);

            // Optionally throw to let parent handle transaction rollback
            throw $e;
        }
    }

    public function finalizeHeadWallData($bridge_id)
    {
        try {
            DB::table('asset_road_bridge_head_wall_details')->insertUsing([
                'head_wall_sr_no',
                'rd_bridge_cd',
                'head_wall_type_cd',
                'head_wall_length',
                'head_wall_width',
                'head_wall_heigth',
                'created_by',
                'created_at',
                'updated_at',
                'head_wall_stream_type_cd',
                'thickness'
            ], function ($query) use ($bridge_id) {
                $query->from('asset_road_bridge_head_wall_draft_details')
                    ->where('rd_bridge_cd', '=', $bridge_id)
                    ->select(
                        'head_wall_sr_no',
                        'rd_bridge_cd',
                        'head_wall_type_cd',
                        'head_wall_length',
                        'head_wall_width',
                        'head_wall_heigth',
                        'created_by',
                        'created_at',
                        'updated_at',
                        'head_wall_stream_type_cd',
                        'thickness'
                    );
            });

            DB::table('asset_road_bridge_head_wall_draft_details')
                ->where('rd_bridge_cd', '=', $bridge_id)
                ->delete();
        } catch (\Throwable $e) {
            Log::error('Failed to finalize wing wall data', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'bridge_id' => $bridge_id
            ]);

            throw $e;
        }
    }

    public function finalizeRetainWallData($bridge_id)
    {
        try {
            DB::table('asset_road_bridge_retain_wall_details')->insertUsing([
                'retain_wall_sr_no',
                'rd_bridge_cd',
                'retain_wall_type_cd',
                'retain_wall_length',
                'retain_wall_width',
                'retain_wall_heigth',
                'created_by',
                'created_at',
                'updated_at',
            ], function ($query) use ($bridge_id) {
                $query->from('asset_road_bridge_retain_wall_draft_details')
                    ->where('rd_bridge_cd', '=', $bridge_id)
                    ->select(
                        'retain_wall_sr_no',
                        'rd_bridge_cd',
                        'retain_wall_type_cd',
                        'retain_wall_length',
                        'retain_wall_width',
                        'retain_wall_heigth',
                        'created_by',
                        'created_at',
                        'updated_at',
                    );
            });

            DB::table('asset_road_bridge_retain_wall_draft_details')
                ->where('rd_bridge_cd', '=', $bridge_id)
                ->delete();
        } catch (\Throwable $e) {
            Log::error('Failed to finalize wing wall data', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'bridge_id' => $bridge_id
            ]);

            throw $e;
        }
    }

    public function finalizeSpanWallData($bridge_id)
    {
        try {
            DB::table('asset_road_bridge_span_details')->insertUsing([
                'span_sr_no',
                'rd_bridge_cd',
                'span_length',
                'created_by',
                'created_at',
                'updated_at',
            ], function ($query) use ($bridge_id) {
                $query->from('asset_road_bridge_span_draft_details')
                    ->where('rd_bridge_cd', '=', $bridge_id)
                    ->select(
                        'span_sr_no',
                        'rd_bridge_cd',
                        'span_length',
                        'created_by',
                        'created_at',
                        'updated_at',
                    );
            });

            DB::table('asset_road_bridge_span_draft_details')
                ->where('rd_bridge_cd', '=', $bridge_id)
                ->delete();
        } catch (\Throwable $e) {
            Log::error('Failed to finalize wing wall data', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'bridge_id' => $bridge_id
            ]);

            throw $e;
        }
    }

    public function finalizePierData($bridge_id)
    {
        try {
            DB::table('asset_road_bridge_pier_details')->insertUsing([
                'pier_sr_no',
                'rd_bridge_cd',
                'pier_type_cd',
                'foundation_type_cd',
                'pier_length',
                'pier_width',
                'pier_heigth',
                'created_by',
                'created_at',
                'updated_at',
                'bearing_type_cd',
                'pile_diameter',
                'pile_length',
                'pile_type_cd',
                'well_type_cd',
                'well_size',
                'open_foundation_size',
                'open_foundation_depth'
            ], function ($query) use ($bridge_id) {
                $query->from('asset_road_bridge_pier_details_draft')
                    ->where('rd_bridge_cd', '=', $bridge_id)
                    ->select(
                        'pier_sr_no',
                        'rd_bridge_cd',
                        'pier_type_cd',
                        'foundation_type_cd',
                        'pier_length',
                        'pier_width',
                        'pier_heigth',
                        'created_by',
                        'created_at',
                        'updated_at',
                        'bearing_type_cd',
                        'pile_diameter',
                        'pile_length',
                        'pile_type_cd',
                        'well_type_cd',
                        'well_size',
                        'open_foundation_size',
                        'open_foundation_depth'
                    );
            });

            DB::table('asset_road_bridge_pier_details_draft')
                ->where('rd_bridge_cd', '=', $bridge_id)
                ->delete();
        } catch (\Throwable $e) {
            Log::error('Failed to finalize wing wall data', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'bridge_id' => $bridge_id
            ]);

            throw $e;
        }
    }

    public function finalizeAbutmentData($bridge_id)
    {
        try {
            DB::table('asset_road_bridge_abutment_wall_details')->insertUsing([
                'abutment_wall_sr_no',
                'rd_bridge_cd',
                'abutment_wall_type_cd',
                'abutment_wall_length',
                'abutment_wall_width',
                'abutment_wall_heigth',
                'created_by',
                'created_at',
                'updated_at',
                'foundation_type_cd',
                'pile_diameter',
                'pile_length',
                'pile_type_cd',
                'well_type_cd',
                'well_size',
                'open_foundation_size',
                'open_foundation_depth',
                'bearing_type_cd'
            ], function ($query) use ($bridge_id) {
                $query->from('asset_road_bridge_abutment_wall_draft_details')
                    ->where('rd_bridge_cd', '=', $bridge_id)
                    ->select(
                        'abutment_wall_sr_no',
                        'rd_bridge_cd',
                        'abutment_wall_type_cd',
                        'abutment_wall_length',
                        'abutment_wall_width',
                        'abutment_wall_heigth',
                        'created_by',
                        'created_at',
                        'updated_at',
                        'foundation_type_cd',
                        'pile_diameter',
                        'pile_length',
                        'pile_type_cd',
                        'well_type_cd',
                        'well_size',
                        'open_foundation_size',
                        'open_foundation_depth',
                        'bearing_type_cd'
                    );
            });

            DB::table('asset_road_bridge_abutment_wall_draft_details')
                ->where('rd_bridge_cd', '=', $bridge_id)
                ->delete();
        } catch (\Throwable $e) {
            Log::error('Failed to finalize wing wall data', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'bridge_id' => $bridge_id
            ]);

            throw $e;
        }
    }
}
