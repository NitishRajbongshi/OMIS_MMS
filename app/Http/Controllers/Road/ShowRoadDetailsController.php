<?php

namespace App\Http\Controllers\Road;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssetMasterDeckType;
use App\Models\AssetMasterHeadWall;
use App\Models\AssetMasterPileType;
use App\Http\Controllers\Controller;
use App\Models\Road\AssetRoadDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetMasterBridgeType;
use App\Models\AssetMasterBearingType;
use App\Models\AssetMasterAbutmentType;
use App\Models\AssetMasterRoadCondition;
use App\Models\AssetMasterExpansionJoint;
use App\Models\AssetMasterFoundationType;
use App\Models\AssetMasterRetainWallType;
use App\Models\AssetMasterConstructionType;
use App\Models\AssetMasterSuperStructureType;
use App\Models\AssetMasterHeadWallsStreamType;
use App\Models\Road\Master\AssetMasterWellType;
use App\Models\Road\Master\AssetMasterWingWallType;
use App\Models\Road\Master\AssetMasterSafetyApronType;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ShowRoadDetailsController extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
        Log::info("Show all road sub-asset Controller");
        $this->middleware("auth");
    }

    public function storeRoadData($roadID)
    {
        $roadDetails = AssetRoadDetail::find($roadID);
        session(['system_id' => $roadID]);
        session(['road_name' => $roadDetails->rd_name]);
        session(['road_number' => $roadDetails->rd_number]);
        session(['road_length' => $roadDetails->road_length]);
    }

    public function showRoadDetails(Request $request)
    {
        $systemId = $request->id;
        $this->storeRoadData($systemId);
        return redirect()->route('showAllRoadModule');
    }

    public function showAllRoadDetails()
    {
        $user = Auth::user();
        $road_system_id = session('system_id');
        $roadDetails = DB::table('asset_road_details')
            ->select(
                'asset_road_details.*',
                'asset_master_road_category.rd_catg_descr',
                'asset_master_rd_type.rd_type_descr',
                'asset_master_road_owner.owner_name',
                'asset_master_office_types.office_type_desc',
                'office_details.office_name'
            )
            ->join('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
            ->join('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
            ->join('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
            ->join('asset_master_office_types', 'asset_road_details.road_created_at_office_type', '=', 'asset_master_office_types.office_type_cd')
            ->join('office_details', 'asset_road_details.road_created_at_office_cd', '=', 'office_details.id')
            ->where('rd_system_id', $road_system_id)
            ->get()->first();

        return view('road.show', compact(
            'user',
            'roadDetails',
        ));
    }

    public function getCDWorkDetails(Request $request)
    {
        $roadID = $request->id;
        $this->storeRoadData($roadID);
        return redirect()->route('showCDWorkDetails');
    }

    public function showCDWorkDetails()
    {
        try {
            $roadID = session('system_id');
            $user = Auth::user();
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
            $draftBaseQuery = DB::table('asset_road_cdwork_details_draft')
                ->select(
                    'asset_road_cdwork_details_draft.*',
                    'asset_master_rd_cdworks_type.cdwoerk_descr',
                    'asset_master_road_condition.rd_condition_descr as cd_condition',
                    'asset_master_culvert_outlet_types.outlet_type_descr',
                    'asset_master_catch_pit_types.catch_pit_type_descr',
                    'asset_master_road_condition_cp.rd_condition_descr as cp_condition',
                    'asset_master_hume_pipe_specifications.hume_pipe_descr',
                    'asset_master_abutment_types.abutment_type_descr',
                    'asset_master_bearing_types.bearing_type_descr',
                    'asset_master_safety_apron_types.apron_type_descr',
                    'asset_master_construction_material_types.const_material_type_descr'
                )
                ->leftJoin('asset_master_rd_cdworks_type', 'asset_road_cdwork_details_draft.culvert_type_cd', '=', 'asset_master_rd_cdworks_type.cdwork_cd')
                ->leftJoin('asset_master_road_condition', 'asset_road_cdwork_details_draft.cdwork_condition', '=', 'asset_master_road_condition.rd_condition_cd')
                ->leftJoin('asset_master_culvert_outlet_types', 'asset_road_cdwork_details_draft.outlet_type_cd', '=', 'asset_master_culvert_outlet_types.outlet_type_cd')
                ->leftJoin('asset_master_catch_pit_types', 'asset_road_cdwork_details_draft.catch_pit_type_cd', '=', 'asset_master_catch_pit_types.catch_pit_type_cd')
                ->leftJoin('asset_master_road_condition AS asset_master_road_condition_cp', 'asset_road_cdwork_details_draft.catch_pit_condition', '=', 'asset_master_road_condition_cp.rd_condition_cd')
                ->leftJoin('asset_master_hume_pipe_specifications', 'asset_road_cdwork_details_draft.pipe_specification', '=', 'asset_master_hume_pipe_specifications.hume_pipe_cd')
                ->leftJoin('asset_master_abutment_types', 'asset_road_cdwork_details_draft.abutment_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
                ->leftJoin('asset_master_bearing_types', 'asset_road_cdwork_details_draft.bearing_type_cd', '=', 'asset_master_bearing_types.bearing_type_cd')
                ->leftJoin('asset_master_safety_apron_types', 'asset_road_cdwork_details_draft.cdwork_safety_apron_type', '=', 'asset_master_safety_apron_types.apron_type_cd')
                ->leftJoin('asset_master_construction_material_types', 'asset_road_cdwork_details_draft.const_material_type_cd', '=', 'asset_master_construction_material_types.const_material_type_cd')
                ->where('asset_road_cdwork_details_draft.rd_system_id', '=', $roadID)
                ->where('asset_road_cdwork_details_draft.sent_for_finalize', '=', 'Y')
                ->orderBy('asset_road_cdwork_details_draft.updated_at', 'desc');

            $finalBaseQuery = DB::table('asset_road_cdwork_details')
                ->select(
                    'asset_road_cdwork_details.*',
                    'asset_master_rd_cdworks_type.cdwoerk_descr',
                    'asset_master_road_condition.rd_condition_descr as cd_condition',
                    'asset_master_culvert_outlet_types.outlet_type_descr',
                    'asset_master_catch_pit_types.catch_pit_type_descr',
                    'asset_master_road_condition_cp.rd_condition_descr as cp_condition',
                    'asset_master_hume_pipe_specifications.hume_pipe_descr',
                    'asset_master_abutment_types.abutment_type_descr',
                    'asset_master_bearing_types.bearing_type_descr',
                    'asset_master_safety_apron_types.apron_type_descr',
                    'asset_master_construction_material_types.const_material_type_descr'
                )
                ->leftJoin('asset_master_rd_cdworks_type', 'asset_road_cdwork_details.culvert_type_cd', '=', 'asset_master_rd_cdworks_type.cdwork_cd')
                ->leftJoin('asset_master_road_condition', 'asset_road_cdwork_details.cdwork_condition', '=', 'asset_master_road_condition.rd_condition_cd')
                ->leftJoin('asset_master_culvert_outlet_types', 'asset_road_cdwork_details.outlet_type_cd', '=', 'asset_master_culvert_outlet_types.outlet_type_cd')
                ->leftJoin('asset_master_catch_pit_types', 'asset_road_cdwork_details.catch_pit_type_cd', '=', 'asset_master_catch_pit_types.catch_pit_type_cd')
                ->leftJoin('asset_master_road_condition AS asset_master_road_condition_cp', 'asset_road_cdwork_details.catch_pit_condition', '=', 'asset_master_road_condition_cp.rd_condition_cd')
                ->leftJoin('asset_master_hume_pipe_specifications', 'asset_road_cdwork_details.pipe_specification', '=', 'asset_master_hume_pipe_specifications.hume_pipe_cd')
                ->leftJoin('asset_master_abutment_types', 'asset_road_cdwork_details.abutment_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
                ->leftJoin('asset_master_bearing_types', 'asset_road_cdwork_details.bearing_type_cd', '=', 'asset_master_bearing_types.bearing_type_cd')
                ->leftJoin('asset_master_safety_apron_types', 'asset_road_cdwork_details.cdwork_safety_apron_type', '=', 'asset_master_safety_apron_types.apron_type_cd')
                ->leftJoin('asset_master_construction_material_types', 'asset_road_cdwork_details.const_material_type_cd', '=', 'asset_master_construction_material_types.const_material_type_cd')
                ->where('asset_road_cdwork_details.rd_system_id', '=', $roadID)
                ->orderBy('asset_road_cdwork_details.updated_at', 'desc');


            if ($users_office_type_cd == 'HQ') {
                $CDWorksDetails = $finalBaseQuery->get();
                $CDWorksDraftDetails = $draftBaseQuery->get();
            }
            if ($users_office_type_cd == 'ZO') {
                $ZOOffices = [];
                $offices = DB::table('office_details')
                    ->where('zone_cd', $zone_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $ZOOffices[] = $item->id;
                }
                $CDWorksDetails = $finalBaseQuery->whereIn('asset_road_cdwork_details.created_at_office_cd', $ZOOffices)->get();
                $CDWorksDraftDetails = $draftBaseQuery->whereIn('asset_road_cdwork_details_draft.created_at_office_cd', $ZOOffices)->get();
            }
            if ($users_office_type_cd == 'CO') {
                $COOffices = [];
                $offices = DB::table('office_details')
                    ->where('circle_cd', $circle_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $COOffices[] = $item->id;
                }
                $CDWorksDetails = $finalBaseQuery->whereIn('asset_road_cdwork_details.created_at_office_cd', $COOffices)->get();
                $CDWorksDraftDetails = $draftBaseQuery->whereIn('asset_road_cdwork_details_draft.created_at_office_cd', $COOffices)->get();
            }
            if ($users_office_type_cd == 'DO') {
                $DOOffices = [];
                $offices = DB::table('office_details')
                    ->where('division_cd', $division_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $DOOffices[] = $item->id;
                }
                $CDWorksDetails = $finalBaseQuery->whereIn('asset_road_cdwork_details.created_at_office_cd', $DOOffices)->get();
                $CDWorksDraftDetails = $draftBaseQuery->whereIn('asset_road_cdwork_details_draft.created_at_office_cd', $DOOffices)->get();
            }
            if ($users_office_type_cd == 'SDO') {
                $SDOffices = [];
                $offices = DB::table('office_details')
                    ->where('sub_division_cd', $sub_division_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $SDOffices[] = $item->id;
                }
                $CDWorksDetails = $finalBaseQuery->whereIn('asset_road_cdwork_details.created_at_office_cd', $SDOffices)->get();
                $CDWorksDraftDetails = $draftBaseQuery->whereIn('asset_road_cdwork_details_draft.created_at_office_cd', $SDOffices)->get();
            }
            return view('road.show_data.cd_works_details', compact(
                'CDWorksDetails',
                'CDWorksDraftDetails',
            ));
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

    public function getBridgeDetails(Request $request)
    {
        $roadID = $request->id;
        $this->storeRoadData($roadID);
        return redirect()->route('showBridgeDetails');
    }

    public function showBridgeDetails()
    {
        try {
            $roadID = session('system_id');
            $userid = Auth::user()->id;
            $cd_bridge_details = DB::table('asset_road_bridge_details_draft')
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
                ->where('created_at_office_cd', '=', auth()->user()->office)
                ->orderBy('updated_at', 'desc')
                ->get();

            $cd_bridge_details_final = DB::table('asset_road_bridge_details')
                ->select(
                    'asset_road_bridge_details.*',
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
                )
                ->leftJoin('asset_master_bridge_type', 'asset_road_bridge_details.bridge_type_cd', '=', 'asset_master_bridge_type.bridge_type_cd')
                ->leftJoin('asset_master_construction_types', 'asset_road_bridge_details.construction_type_cd', '=', 'asset_master_construction_types.construction_type_cd')
                // ->leftJoin('asset_master_foundation_types', 'asset_road_bridge_details.foundation_type_cd', '=', 'asset_master_foundation_types.foundation_cd')
                ->leftJoin('asset_master_abutment_types', 'asset_road_bridge_details.abutment_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
                ->leftJoin('asset_master_super_structure_types', 'asset_road_bridge_details.super_structure_type_cd', '=', 'asset_master_super_structure_types.st_type_cd')
                ->leftJoin('asset_master_handrail_types', 'asset_road_bridge_details.handrail_type_cd', '=', 'asset_master_handrail_types.hand_rail_type_cd')
                ->leftJoin('asset_master_deck_types', 'asset_road_bridge_details.deck_type_cd', '=', 'asset_master_deck_types.deck_type_cd')
                // ->leftJoin('asset_master_bearing_types', 'asset_road_bridge_details.bearings', '=', 'asset_master_bearing_types.bearing_type_cd')
                ->leftJoin('asset_master_expansion_joints', 'asset_road_bridge_details.expansion_join_cd', '=', 'asset_master_expansion_joints.expn_joint_cd')
                ->leftJoin('asset_master_road_condition', 'asset_road_bridge_details.bridge_condition', '=', 'asset_master_road_condition.rd_condition_cd')
                // ->leftJoin('asset_master_pile_types', 'asset_road_bridge_details.pile_type', '=', 'asset_master_pile_types.pile_type_cd')
                // ->leftJoin('asset_master_well_types', 'asset_road_bridge_details.well_type', '=', 'asset_master_well_types.well_type_cd')
                ->where('asset_road_bridge_details.rd_system_id', '=', $roadID)
                ->where('created_at_office_cd', '=', auth()->user()->office)
                ->orderBy('updated_at', 'desc')
                ->get();

            $bridgeTypes = AssetMasterBridgeType::all();
            $constructionTypes = AssetMasterConstructionType::all();
            $foundationTypes = AssetMasterFoundationType::all();
            $abutmentTypes = AssetMasterAbutmentType::all();
            $superStructureType = AssetMasterSuperStructureType::all();
            $deckTypes = AssetMasterDeckType::all();
            $expJoints = AssetMasterExpansionJoint::all();
            $bridgeConditions = AssetMasterRoadCondition::all();
            $bearingTypes = AssetMasterBearingType::all();
            $pileTypes = AssetMasterPileType::all();
            $wellTypes = AssetMasterWellType::all();
            $headWalls = AssetMasterHeadWall::all();
            $retainWalls = AssetMasterRetainWallType::all();
            $wingWallTypes = AssetMasterWingWallType::all();
            $saftyApronTypes = AssetMasterSafetyApronType::all();
            $streamTypes = AssetMasterHeadWallsStreamType::all();

            return view('road.show_data.bridge_details', compact(
                'cd_bridge_details_final',
                'bridgeTypes',
                'constructionTypes',
                'foundationTypes',
                'abutmentTypes',
                'superStructureType',
                'deckTypes',
                'expJoints',
                'bearingTypes',
                'bridgeConditions',
                'cd_bridge_details',
                'pileTypes',
                'wellTypes',
                'headWalls',
                'retainWalls',
                'wingWallTypes',
                'saftyApronTypes',
                'streamTypes'
            ));
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

    public function getSurfaceTypeDetails(Request $request)
    {
        $roadID = $request->id;
        $this->storeRoadData($roadID);
        return redirect()->route('showSurfaceTypeDetails');
    }

    public function showSurfaceTypeDetails()
    {
        try {
            $roadID = session('system_id');
            $userid = Auth::user()->id;
            $surfaceTypeDetailsDraft = DB::table('asset_road_surface_type_details_draft')
                ->select('asset_road_surface_type_details_draft.*', 'asset_master_surface_type.surface_descr', 'asset_master_road_condition.rd_condition_descr', 'office_details.office_name', 'asset_master_base_layer_types.base_layer_type_descr', 'asset_master_sub_base_layer_types.sub_base_layer_type_descr', 'asset_master_pavement_types.pavement_type_descr', 'asset_master_shoulder_types.shoulder_type_descr', "asset_master_maintenance_types.maintenance_type_descr", 'asset_master_drainage_types.drainage_descr')
                ->leftJoin('asset_master_surface_type', 'asset_road_surface_type_details_draft.surface_type_cd', '=', 'asset_master_surface_type.surface_cd')
                ->leftJoin('asset_master_road_condition', 'asset_road_surface_type_details_draft.surface_condition_cd', '=', 'asset_master_road_condition.rd_condition_cd')
                ->leftJoin('office_details', 'asset_road_surface_type_details_draft.created_at_office_cd', '=', 'office_details.id')
                ->leftJoin('asset_master_base_layer_types', 'asset_road_surface_type_details_draft.base_layer_type', '=', 'asset_master_base_layer_types.base_layer_type_cd')
                ->leftJoin('asset_master_sub_base_layer_types', 'asset_road_surface_type_details_draft.sub_base_layer_type', '=', 'asset_master_sub_base_layer_types.sub_base_layer_type_cd')
                ->leftJoin('asset_master_pavement_types', 'asset_road_surface_type_details_draft.pavement_type', '=', 'asset_master_pavement_types.pavement_type_cd')
                ->leftJoin('asset_master_shoulder_types', 'asset_road_surface_type_details_draft.shoulder_type', '=', 'asset_master_shoulder_types.shoulder_type_cd')
                ->leftJoin('asset_master_maintenance_types', 'asset_road_surface_type_details_draft.maintenance_type', '=', 'asset_master_maintenance_types.maintenance_type_cd')
                ->leftJoin('asset_master_drainage_types', 'asset_road_surface_type_details_draft.drainage', '=', 'asset_master_drainage_types.drainage_cd')
                ->where('rd_system_id', '=', $roadID)
                ->where('sent_for_finalize', '=', 'Y')
                ->where('created_at_office_cd', '=', auth()->user()->office)
                ->orderBy('id', 'desc')
                ->get();

            $surfaceTypeDetailsFinal = DB::table('asset_road_surface_type_details')
                ->select('asset_road_surface_type_details.*', 'asset_master_surface_type.surface_descr', 'asset_master_road_condition.rd_condition_descr', 'office_details.office_name', 'asset_master_base_layer_types.base_layer_type_descr', 'asset_master_sub_base_layer_types.sub_base_layer_type_descr', 'asset_master_pavement_types.pavement_type_descr', 'asset_master_shoulder_types.shoulder_type_descr', "asset_master_maintenance_types.maintenance_type_descr", 'asset_master_drainage_types.drainage_descr')
                ->leftJoin('asset_master_surface_type', 'asset_road_surface_type_details.surface_type_cd', '=', 'asset_master_surface_type.surface_cd')
                ->leftJoin('asset_master_road_condition', 'asset_road_surface_type_details.surface_condition_cd', '=', 'asset_master_road_condition.rd_condition_cd')
                ->leftJoin('office_details', 'asset_road_surface_type_details.created_at_office_cd', '=', 'office_details.id')
                ->leftJoin('asset_master_base_layer_types', 'asset_road_surface_type_details.base_layer_type', '=', 'asset_master_base_layer_types.base_layer_type_cd')
                ->leftJoin('asset_master_sub_base_layer_types', 'asset_road_surface_type_details.sub_base_layer_type', '=', 'asset_master_sub_base_layer_types.sub_base_layer_type_cd')
                ->leftJoin('asset_master_pavement_types', 'asset_road_surface_type_details.pavement_type', '=', 'asset_master_pavement_types.pavement_type_cd')
                ->leftJoin('asset_master_shoulder_types', 'asset_road_surface_type_details.shoulder_type', '=', 'asset_master_shoulder_types.shoulder_type_cd')
                ->leftJoin('asset_master_maintenance_types', 'asset_road_surface_type_details.maintenance_type', '=', 'asset_master_maintenance_types.maintenance_type_cd')
                ->leftJoin('asset_master_drainage_types', 'asset_road_surface_type_details.drainage', '=', 'asset_master_drainage_types.drainage_cd')
                ->where('rd_system_id', '=', $roadID)
                ->where('created_at_office_cd', '=', auth()->user()->office)
                ->orderBy('id', 'desc')
                ->get();

            return view('road.show_data.surface_type_details', compact(
                'surfaceTypeDetailsDraft',
                'surfaceTypeDetailsFinal'
            ));
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }

    public function getPCIDetails(Request $request)
    {
        $roadID = $request->id;
        $this->storeRoadData($roadID);
        return redirect()->route('showPCIDetails');
    }

    public function showPCIDetails()
    {
        try {
            $roadID = session('system_id');
            $userid = Auth::user()->id;
            $pciDetailsDraft = DB::table('asset_road_pavement_condition_indexes_draft')
                ->select('*')
                ->where('rd_system_id', '=', $roadID)
                ->where('sent_for_finalize', '=', 'Y')
                ->get();
            $pciDetailsFinal = DB::table('asset_road_pavement_condition_indexes')
                ->select('*')
                ->where('rd_system_id', '=', $roadID)
                ->get();
            return view('road.show_data.pci_details', compact(
                'pciDetailsDraft',
                'pciDetailsFinal'
            ));
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }

    public function getProtectionWallDetails(Request $request)
    {
        $roadID = $request->id;
        $this->storeRoadData($roadID);
        return redirect()->route('showProtectionWallDetails');
    }

    public function showProtectionWallDetails()
    {
        try {
            $roadID = session('system_id');
            $userid = Auth::user()->id;
            $draftDetails = DB::table('asset_protection_wall_details_draft')
                ->select(
                    'asset_protection_wall_details_draft.*',
                    'asset_master_protection_wall_type.wall_type_descr',
                    'asset_master_protection_wall_structure_type.structure_type_descr',
                )
                ->leftJoin('asset_master_protection_wall_type', 'asset_protection_wall_details_draft.wall_type_cd', '=', 'asset_master_protection_wall_type.wall_type_cd')
                ->leftJoin('asset_master_protection_wall_structure_type', 'asset_protection_wall_details_draft.structure_type_cd', '=', 'asset_master_protection_wall_structure_type.structure_type_cd')
                ->where('asset_protection_wall_details_draft.rd_system_id', '=', $roadID)
                ->where('sent_for_finalize', '=', 'Y')
                ->get();

            $finalizedDetails = DB::table('asset_protection_wall_details')
                ->select(
                    'asset_protection_wall_details.*',
                    'asset_master_protection_wall_type.wall_type_descr',
                    'asset_master_protection_wall_structure_type.structure_type_descr',
                )
                ->leftJoin('asset_master_protection_wall_type', 'asset_protection_wall_details.wall_type_cd', '=', 'asset_master_protection_wall_type.wall_type_cd')
                ->leftJoin('asset_master_protection_wall_structure_type', 'asset_protection_wall_details.structure_type_cd', '=', 'asset_master_protection_wall_structure_type.structure_type_cd')
                ->where('asset_protection_wall_details.rd_system_id', '=', $roadID)
                ->get();
            return view('road.show_data.protection_wall', compact(
                'draftDetails',
                'finalizedDetails'
            ));
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }

    public function getHabitationDetails(Request $request)
    {
        $roadID = $request->id;
        $this->storeRoadData($roadID);
        return redirect()->route('showHabitationDetails');
    }

    public function showHabitationDetails()
    {
        try {
            $roadID = session('system_id');
            $userid = Auth::user()->id;
            $habitationDetailsDraft = DB::table('asset_road_habitation_details_draft')
                ->select('*')
                ->where('rd_system_id', '=', $roadID)
                ->where('sent_for_finalize', '=', 'Y')
                ->get();

            $habitationCdsDraft = $habitationDetailsDraft->pluck('habitation_cd');
            $habitationFacilitiesDraft = DB::table('asset_road_habitation_facility_details_draft as hf')
                ->select(
                    'hf.id',
                    'hf.facility_id',
                    'hf.sub_facility_id',
                    'hf.habitation_cd',
                    'f.facility_name',
                    'sf.sub_facility_name'
                )
                ->leftJoin('asset_master_habitation_facilities as f', 'hf.facility_id', '=', 'f.id')
                ->leftJoin('asset_master_habitation_sub_facilities as sf', 'hf.sub_facility_id', '=', 'sf.id')
                ->whereIn('hf.habitation_cd', $habitationCdsDraft)
                ->get()
                ->groupBy('habitation_cd');
            $habitationDetailsFinal = DB::table('asset_road_habitation_details')
                ->select('*')
                ->where('rd_system_id', '=', $roadID)
                ->get();

            $habitationCds = $habitationDetailsFinal->pluck('habitation_cd');
            $habitationFacilities = DB::table('asset_road_habitation_facility_details as hf')
                ->select(
                    'hf.id',
                    'hf.facility_id',
                    'hf.sub_facility_id',
                    'hf.habitation_cd',
                    'f.facility_name',
                    'sf.sub_facility_name'
                )
                ->leftJoin('asset_master_habitation_facilities as f', 'hf.facility_id', '=', 'f.id')
                ->leftJoin('asset_master_habitation_sub_facilities as sf', 'hf.sub_facility_id', '=', 'sf.id')
                ->whereIn('hf.habitation_cd', $habitationCds)
                ->get()
                ->groupBy('habitation_cd');
            return view('road.show_data.habitation_details', compact(
                'habitationDetailsDraft',
                'habitationFacilitiesDraft',
                'habitationDetailsFinal',
                'habitationFacilities'
            ));
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }
}
