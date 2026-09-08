<?php

namespace App\Http\Controllers\FinalizeRoadsAndBridges;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssetMasterHeadWall;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetMasterToeWallType;
use App\Models\AssetMasterCatchPitType;
use App\Models\AssetMasterFaceWallType;
use App\Models\AssetMasterRdCdWorksType;
use App\Models\AssetMasterRoadCondition;
use App\Models\AssetMasterCulvertOutletType;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

class FinalizeCDWorksController extends Controller
{
    public $cdWorkTypes;
    public $headWalls;
    public $roadConditions;
    public $faceWallTypes;
    public $outletTypes;
    public $pitTypes;
    public $toeWallTypes;

    public function __construct()
    {
        DB::enableQueryLog();
        Log::info("Finalize CD Works Controller");
        $this->middleware("auth");
        $this->cdWorkTypes = AssetMasterRdCdWorksType::all();
        $this->headWalls = AssetMasterHeadWall::all();
        $this->roadConditions = AssetMasterRoadCondition::all();
        $this->faceWallTypes = AssetMasterFaceWallType::all();
        $this->outletTypes = AssetMasterCulvertOutletType::all();
        $this->pitTypes = AssetMasterCatchPitType::all();
        $this->toeWallTypes = AssetMasterToeWallType::all();
    }

    public function load()
    {
        try {
            return redirect()->route('finalize.road.cdworks');
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('errors.generic');
        }
    }

    public function index()
    {
        try {
            $user = Auth::user();
            $roadID = session('system_id');
            $cdWorkTypes = $this->cdWorkTypes;
            $headWalls = $this->headWalls;
            $roadConditions = $this->roadConditions;
            $faceWallTypes = $this->faceWallTypes;
            $outletTypes = $this->outletTypes;
            $pitTypes = $this->pitTypes;
            $toeWallTypes = $this->toeWallTypes;
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
            $baseQuery = DB::table('asset_road_cdwork_details_draft')
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
                    'asset_master_construction_material_types.const_material_type_descr',
                    //saiful 23-04-2026 Start
                    'pp.project_cd'
                    //saiful 23-04-2026 End
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
                //saiful 23-04-2026 Start
                ->leftJoin('prt_project_asset_plan as pp', 'asset_road_cdwork_details_draft.asset_plan_id', '=', 'pp.id')
                //saiful 23-04-2026 End
                ->where('asset_road_cdwork_details_draft.rd_system_id', '=', $roadID)
                ->where('asset_road_cdwork_details_draft.sent_for_finalize', '=', 'Y')
                ->orderBy('asset_road_cdwork_details_draft.updated_at', 'desc');
            if ($users_office_type_cd == 'HQ') {
                $CDWorksDetails = $baseQuery->get();
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
                $CDWorksDetails = $baseQuery->whereIn('asset_road_cdwork_details_draft.created_at_office_cd', $ZOOffices)->get();
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
                $CDWorksDetails = $baseQuery->whereIn('asset_road_cdwork_details_draft.created_at_office_cd', $COOffices)->get();
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
                $CDWorksDetails = $baseQuery->whereIn('asset_road_cdwork_details_draft.created_at_office_cd', $DOOffices)->get();
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
                $CDWorksDetails = $baseQuery->whereIn('asset_road_cdwork_details_draft.created_at_office_cd', $SDOffices)->get();
            }
            $query = DB::getQueryLog();
            Log::info($query);
            return view('road.finalize.cdworks', compact(
                'roadID',
                'user',
                'pitTypes',
                'headWalls',
                'cdWorkTypes',
                'outletTypes',
                'toeWallTypes',
                'faceWallTypes',
                'roadConditions',
                'CDWorksDetails',
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

    public function store(Request $request)
    {
        try {
            if (isset($request->_token)) {
                $roadID = session('roadId');
                $createdBy = Auth::user()->id;
                $currentTime = now();

                // finalize the wing wall data
                $culvertId = DB::table('asset_road_cdwork_details_draft')
                    ->select('rd_cdwork_cd')
                    ->where('rd_system_id', '=', $roadID)
                    ->where('sent_for_finalize', '=', 'Y')
                    ->get();

                // loop through the items
                foreach ($culvertId as $id) {
                    $this->finalizeWingWallData($id->rd_cdwork_cd);
                }

                // loop through the items
                foreach ($culvertId as $id) {
                    $this->finalizeHeadWallData($id->rd_cdwork_cd);
                }

                $status = DB::table('asset_road_cdwork_details')->insertUsing([
                    'rd_cdwork_cd',
                    'rd_system_id',
                    'culvert_no',
                    'chainage',
                    'culvert_type_cd',
                    'cussion',
                    'cdwork_size',
                    'cdwork_width',
                    'cdwork_height',
                    'cdwork_length',
                    'cdwork_outlet',
                    'cdwork_no_of_vents',
                    'cdwork_thickness_side_wall',
                    'cdwork_thickness_top_slab',
                    'cdwork_thickness_bottom_slab',
                    'cdwork_has_safety_apron',
                    'cdwork_apron_width',
                    'cdwork_has_wing_wall',
                    'cdwork_condition',
                    'discharge',
                    'year_of_construction',
                    'year_of_rehabilitation',
                    'span',
                    'carriage_way',
                    'created_at_office_cd',
                    'no_of_rows',
                    'pipe_diameter',
                    'pipe_length',
                    'pipe_specification',
                    'slab_thickness',
                    'slab_length',
                    'slab_width',
                    'vent_height',
                    'no_of_wing_wall',
                    'no_of_cell',
                    'width_each_cell',
                    'heigth_each_cell',
                    'height_of_earth_cushion',
                    'culvert_location',
                    'no_of_opening',
                    'outlet_type_cd',
                    'catch_pit_availability',
                    'catch_pit_type_cd',
                    'catch_pit_size',
                    'catch_pit_condition',
                    'catch_toe_wall_size',
                    'cdwork_remark',
                    'cdwork_has_head_wall',
                    'cdwork_safety_apron_type',
                    'cdwork_safety_apron_hand_rail_type',
                    'created_at',
                    'updated_at',
                    'updated_by',
                    'created_by',
                    //saiful # 29-04-2026 # Start
                    'asset_plan_id'
                    //saiful # 29-04-2026 # End
                ], function ($query) use ($roadID, $currentTime, $createdBy) {
                    $query->from('asset_road_cdwork_details_draft')
                        ->where('rd_system_id', '=', $roadID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'rd_cdwork_cd',
                            'rd_system_id',
                            'culvert_no',
                            'chainage',
                            'culvert_type_cd',
                            'cussion',
                            'cdwork_size',
                            'cdwork_width',
                            'cdwork_height',
                            'cdwork_length',
                            'cdwork_outlet',
                            'cdwork_no_of_vents',
                            'cdwork_thickness_side_wall',
                            'cdwork_thickness_top_slab',
                            'cdwork_thickness_bottom_slab',
                            'cdwork_has_safety_apron',
                            'cdwork_apron_width',
                            'cdwork_has_wing_wall',
                            'cdwork_condition',
                            'discharge',
                            'year_of_construction',
                            'year_of_rehabilitation',
                            'span',
                            'carriage_way',
                            'created_at_office_cd',
                            'no_of_rows',
                            'pipe_diameter',
                            'pipe_length',
                            'pipe_specification',
                            'slab_thickness',
                            'slab_length',
                            'slab_width',
                            'vent_height',
                            'no_of_wing_wall',
                            'no_of_cell',
                            'width_each_cell',
                            'heigth_each_cell',
                            'height_of_earth_cushion',
                            'culvert_location',
                            'no_of_opening',
                            'outlet_type_cd',
                            'catch_pit_availability',
                            'catch_pit_type_cd',
                            'catch_pit_size',
                            'catch_pit_condition',
                            'catch_toe_wall_size',
                            'cdwork_remark',
                            'cdwork_has_head_wall',
                            'cdwork_safety_apron_type',
                            'cdwork_safety_apron_hand_rail_type',
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
                    DB::table('asset_road_cdwork_details_draft')
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
            $query = DB::getQueryLog();
            Log::info($query);
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

    public function acceptSingleCDWorksData(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $culvertID = $request->id;
                $approvedBy = Auth::user()->id;
                $currentTime = now();
                $this->finalizeWingWallData($culvertID);
                $this->finalizeHeadWallData($culvertID);
                //saiful # 29-04-2026 # Start
                //check if same bridge code exist in main table or not, if exist then update otherwise insert
                $existingBridge = DB::table('asset_road_cdwork_details')
                    ->where('rd_cdwork_cd', $culvertID)
                    ->first();
                if ($existingBridge) {
                    return $this->handleApprovalOfExistingCulvert($culvertID, $approvedBy, $currentTime);
                } else {
                    //saiful # 29-04-2026 # End
                    $status = DB::table('asset_road_cdwork_details')->insertUsing([
                        'rd_cdwork_cd',
                        'rd_system_id',
                        'culvert_no',
                        'chainage',
                        'culvert_type_cd',
                        'cussion',
                        'cdwork_outlet',
                        'cdwork_no_of_vents',
                        'cdwork_thickness_side_wall',
                        'cdwork_thickness_top_slab',
                        'cdwork_thickness_bottom_slab',
                        'cdwork_has_wing_wall',
                        'cdwork_condition',
                        'discharge',
                        'year_of_construction',
                        'year_of_rehabilitation',
                        'span',
                        'created_at',
                        'updated_at',
                        'created_by',
                        'updated_by',
                        'created_at_office_cd',
                        'no_of_rows',
                        'pipe_diameter',
                        'culvert_width',
                        'pipe_specification',
                        'length_span',
                        'no_of_wing_wall',
                        'width_each_cell',
                        'heigth_each_cell',
                        'slab_thickness',
                        'height_of_earth_cushion',
                        'slab_length',
                        'slab_width',
                        'no_of_cell',
                        'culvert_location',
                        'outlet_type_cd',
                        'catch_pit_availability',
                        'catch_pit_type_cd',
                        'catch_pit_width',
                        'catch_pit_condition',
                        'cdwork_remark',
                        'cdwork_has_head_wall',
                        'const_material_type_cd',
                        'abutment_type_cd',
                        'abutment_height',
                        'bearing_type_cd',
                        'cdwork_has_safety_apron',
                        'cdwork_safety_apron_type',
                        'cdwork_safety_apron_outlet',
                        'cdwork_safety_apron_width',
                        'cdwork_safety_apron_length',
                        'cdwork_safety_apron_slab_thickness',
                        'cdwork_safety_apron_hand_rail_type',
                        'catch_pit_heigth',
                        'catch_pit_breadth',
                        'catch_pit_thickness',
                        'approved_by',
                        'approved_at',
                        //saiful # 29-04-2026 # Start
                        'asset_plan_id'
                        //saiful # 29-04-2026 # End
                    ], function ($query) use ($culvertID, $currentTime, $approvedBy) {
                        $query->from('asset_road_cdwork_details_draft')
                            ->where('rd_cdwork_cd', '=', $culvertID)
                            ->where('sent_for_finalize', '=', 'Y')
                            ->select(
                                'rd_cdwork_cd',
                                'rd_system_id',
                                'culvert_no',
                                'chainage',
                                'culvert_type_cd',
                                'cussion',
                                'cdwork_outlet',
                                'cdwork_no_of_vents',
                                'cdwork_thickness_side_wall',
                                'cdwork_thickness_top_slab',
                                'cdwork_thickness_bottom_slab',
                                'cdwork_has_wing_wall',
                                'cdwork_condition',
                                'discharge',
                                'year_of_construction',
                                'year_of_rehabilitation',
                                'span',
                                'created_at',
                                'updated_at',
                                'created_by',
                                'updated_by',
                                'created_at_office_cd',
                                'no_of_rows',
                                'pipe_diameter',
                                'culvert_width',
                                'pipe_specification',
                                'length_span',
                                'no_of_wing_wall',
                                'width_each_cell',
                                'heigth_each_cell',
                                'slab_thickness',
                                'height_of_earth_cushion',
                                'slab_length',
                                'slab_width',
                                'no_of_cell',
                                'culvert_location',
                                'outlet_type_cd',
                                'catch_pit_availability',
                                'catch_pit_type_cd',
                                'catch_pit_width',
                                'catch_pit_condition',
                                'cdwork_remark',
                                'cdwork_has_head_wall',
                                'const_material_type_cd',
                                'abutment_type_cd',
                                'abutment_height',
                                'bearing_type_cd',
                                'cdwork_has_safety_apron',
                                'cdwork_safety_apron_type',
                                'cdwork_safety_apron_outlet',
                                'cdwork_safety_apron_width',
                                'cdwork_safety_apron_length',
                                'cdwork_safety_apron_slab_thickness',
                                'cdwork_safety_apron_hand_rail_type',
                                'catch_pit_heigth',
                                'catch_pit_breadth',
                                'catch_pit_thickness',
                                DB::raw("'$approvedBy' as approved_by"),
                                DB::raw("'$currentTime' as approved_at"),
                                //saiful # 29-04-2026 # Start
                                'asset_plan_id'
                                //saiful # 29-04-2026 # End
                            );
                    });
                    if ($status > 0) {
                        DB::table('asset_road_cdwork_details_draft')
                            ->where('rd_cdwork_cd', '=', $culvertID)
                            ->where('sent_for_finalize', '=', 'Y')
                            ->delete();
                        return response()->json([
                            'status' => 200,
                            'message' => 'Finalize data successfully!'
                        ]);
                    } else {
                        return response()->json([
                            'status' => 204,
                            'message' => 'Data not available to finalize!'
                        ]);
                    }
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
    //saiful # 29-04-2026 # Start
    private function handleApprovalOfExistingCulvert($culvertID, $approvedBy, $currentTime)
    {
        Log::info("Culvert with ID $culvertID already exists. Updating existing record.");
        $culvertDraftInfos = DB::table('asset_road_cdwork_details_draft')
            ->where('rd_cdwork_cd', '=', $culvertID)
            ->where('sent_for_finalize', '=', 'Y')
            ->first();
        $status = DB::table('asset_road_cdwork_details_hist')->insertUsing([
            'rd_cdwork_cd',
            'rd_system_id',
            'culvert_no',
            'chainage',
            'culvert_type_cd',
            'cussion',
            'cdwork_outlet',
            'cdwork_no_of_vents',
            'cdwork_thickness_side_wall',
            'cdwork_thickness_top_slab',
            'cdwork_thickness_bottom_slab',
            'cdwork_has_wing_wall',
            'cdwork_condition',
            'discharge',
            'year_of_construction',
            'year_of_rehabilitation',
            'span',
            'no_of_rows',
            'pipe_diameter',
            'culvert_width',
            'pipe_specification',
            'length_span',
            'no_of_wing_wall',
            'width_each_cell',
            'heigth_each_cell',
            'slab_thickness',
            'height_of_earth_cushion',
            'no_of_cell',
            'culvert_location',
            'outlet_type_cd',
            'catch_pit_availability',
            'catch_pit_type_cd',
            'catch_pit_width',
            'catch_pit_condition',
            'cdwork_remark',
            'slab_length',
            'slab_width',
            'created_at_office_cd',
            'const_material_type_cd',
            'abutment_type_cd',
            'abutment_height',
            'bearing_type_cd',
            'cdwork_has_safety_apron',
            'cdwork_safety_apron_type',
            'cdwork_safety_apron_outlet',
            'cdwork_safety_apron_width',
            'cdwork_safety_apron_length',
            'cdwork_safety_apron_slab_thickness',
            'cdwork_safety_apron_hand_rail_type',
            'catch_pit_heigth',
            'catch_pit_breadth',
            'catch_pit_thickness',
            'approved_by',
            'approved_at',
            'asset_plan_id',
            'created_at',
            'updated_at',
            'updated_by',
            'created_by',
            'hist_remarks',
            'hist_created_by',
            'hist_created_on'
        ], function ($query) use ($culvertID, $currentTime, $approvedBy) {
            $query->from('asset_road_cdwork_details')
                ->where('rd_cdwork_cd', '=', $culvertID)
                ->select(
                    'rd_cdwork_cd',
                    'rd_system_id',
                    'culvert_no',
                    'chainage',
                    'culvert_type_cd',
                    'cussion',
                    'cdwork_outlet',
                    'cdwork_no_of_vents',
                    'cdwork_thickness_side_wall',
                    'cdwork_thickness_top_slab',
                    'cdwork_thickness_bottom_slab',
                    'cdwork_has_wing_wall',
                    'cdwork_condition',
                    'discharge',
                    'year_of_construction',
                    'year_of_rehabilitation',
                    'span',
                    'no_of_rows',
                    'pipe_diameter',
                    'culvert_width',
                    'pipe_specification',
                    'length_span',
                    'no_of_wing_wall',
                    'width_each_cell',
                    'heigth_each_cell',
                    'slab_thickness',
                    'height_of_earth_cushion',
                    'no_of_cell',
                    'culvert_location',
                    'outlet_type_cd',
                    'catch_pit_availability',
                    'catch_pit_type_cd',
                    'catch_pit_width',
                    'catch_pit_condition',
                    'cdwork_remark',
                    'slab_length',
                    'slab_width',
                    'created_at_office_cd',
                    'const_material_type_cd',
                    'abutment_type_cd',
                    'abutment_height',
                    'bearing_type_cd',
                    'cdwork_has_safety_apron',
                    'cdwork_safety_apron_type',
                    'cdwork_safety_apron_outlet',
                    'cdwork_safety_apron_width',
                    'cdwork_safety_apron_length',
                    'cdwork_safety_apron_slab_thickness',
                    'cdwork_safety_apron_hand_rail_type',
                    'catch_pit_heigth',
                    'catch_pit_breadth',
                    'catch_pit_thickness',
                    'approved_by',
                    'approved_at',
                    'asset_plan_id',
                    'created_at',
                    'updated_at',
                    'updated_by',
                    'created_by',
                    DB::raw("'Asset Redefined by Project' as hist_remarks"),
                    DB::raw("'$approvedBy' as hist_created_by"),
                    DB::raw("'$currentTime' as hist_created_on")
                );
        });
        if ($status > 0) {
            $status = DB::table('public.asset_road_cdwork_details')
                ->where('rd_cdwork_cd', '=', $culvertID)
                ->update([
                    'rd_cdwork_cd' => $culvertDraftInfos->rd_cdwork_cd,
                    'rd_system_id' => $culvertDraftInfos->rd_system_id,
                    'culvert_no' => $culvertDraftInfos->culvert_no,
                    'chainage' => $culvertDraftInfos->chainage,
                    'culvert_type_cd' => $culvertDraftInfos->culvert_type_cd,
                    'cussion' => $culvertDraftInfos->cussion,
                    'cdwork_outlet' => $culvertDraftInfos->cdwork_outlet,
                    'cdwork_no_of_vents' => $culvertDraftInfos->cdwork_no_of_vents,
                    'cdwork_thickness_side_wall' => $culvertDraftInfos->cdwork_thickness_side_wall,
                    'cdwork_thickness_top_slab' => $culvertDraftInfos->cdwork_thickness_top_slab,
                    'cdwork_thickness_bottom_slab' => $culvertDraftInfos->cdwork_thickness_bottom_slab,
                    'cdwork_has_wing_wall' => $culvertDraftInfos->cdwork_has_wing_wall,
                    'cdwork_condition' => $culvertDraftInfos->cdwork_condition,
                    'discharge' => $culvertDraftInfos->discharge,
                    'year_of_construction' => $culvertDraftInfos->year_of_construction,
                    'year_of_rehabilitation' => $culvertDraftInfos->year_of_rehabilitation,
                    'span' => $culvertDraftInfos->span,
                    'no_of_rows' => $culvertDraftInfos->no_of_rows,
                    'pipe_diameter' => $culvertDraftInfos->pipe_diameter,
                    'culvert_width' => $culvertDraftInfos->culvert_width,
                    'pipe_specification' => $culvertDraftInfos->pipe_specification,
                    'length_span' => $culvertDraftInfos->length_span,
                    'no_of_wing_wall' => $culvertDraftInfos->no_of_wing_wall,
                    'width_each_cell' => $culvertDraftInfos->width_each_cell,
                    'heigth_each_cell' => $culvertDraftInfos->heigth_each_cell,
                    'slab_thickness' => $culvertDraftInfos->slab_thickness,
                    'height_of_earth_cushion' => $culvertDraftInfos->height_of_earth_cushion,
                    'no_of_cell' => $culvertDraftInfos->no_of_cell,
                    'culvert_location' => $culvertDraftInfos->culvert_location,
                    'outlet_type_cd' => $culvertDraftInfos->outlet_type_cd,
                    'catch_pit_availability' => $culvertDraftInfos->catch_pit_availability,
                    'catch_pit_type_cd' => $culvertDraftInfos->catch_pit_type_cd,
                    'catch_pit_width' => $culvertDraftInfos->catch_pit_width,
                    'catch_pit_condition' => $culvertDraftInfos->catch_pit_condition,
                    'cdwork_remark' => $culvertDraftInfos->cdwork_remark,
                    'slab_length' => $culvertDraftInfos->slab_length,
                    'slab_width' => $culvertDraftInfos->slab_width,
                    'created_at_office_cd' => $culvertDraftInfos->created_at_office_cd,
                    'const_material_type_cd' => $culvertDraftInfos->const_material_type_cd,
                    'abutment_type_cd' => $culvertDraftInfos->abutment_type_cd,
                    'abutment_height' => $culvertDraftInfos->abutment_height,
                    'bearing_type_cd' => $culvertDraftInfos->bearing_type_cd,
                    'cdwork_has_safety_apron' => $culvertDraftInfos->cdwork_has_safety_apron,
                    'cdwork_safety_apron_type' => $culvertDraftInfos->cdwork_safety_apron_type,
                    'cdwork_safety_apron_outlet' => $culvertDraftInfos->cdwork_safety_apron_outlet,
                    'cdwork_safety_apron_width' => $culvertDraftInfos->cdwork_safety_apron_width,
                    'cdwork_safety_apron_length' => $culvertDraftInfos->cdwork_safety_apron_length,
                    'cdwork_safety_apron_slab_thickness' => $culvertDraftInfos->cdwork_safety_apron_slab_thickness,
                    'cdwork_safety_apron_hand_rail_type' => $culvertDraftInfos->cdwork_safety_apron_hand_rail_type,
                    'catch_pit_heigth' => $culvertDraftInfos->catch_pit_heigth,
                    'catch_pit_breadth' => $culvertDraftInfos->catch_pit_breadth,
                    'catch_pit_thickness' => $culvertDraftInfos->catch_pit_thickness,
                    'approved_by' => $approvedBy,
                    'approved_at' => $currentTime,
                    'asset_plan_id' => $culvertDraftInfos->asset_plan_id,
                    'updated_at' => now(),
                    'updated_by' => auth()->id()

                ]);
            DB::table('asset_road_cdwork_details_draft')
                ->where('rd_cdwork_cd', '=', $culvertID)
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
    public function rejectSingleCDWorksData(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $culvertID = $request->id;

                $status = DB::table('asset_road_cdwork_details_draft')
                    ->where('sent_for_finalize', 'Y')
                    ->where('rd_cdwork_cd', $culvertID)
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
                        'message' => 'Culvert data rejected!'
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

    public function finalizeWingWallData($culvert_id)
    {
        $id = $culvert_id;
        $status = DB::table('asset_road_cdwork_wing_wall_details')->insertUsing([
            'wing_wall_sr_no',
            'rd_cdwork_cd',
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
        ], function ($query) use ($id) {
            $query->from('asset_road_cdwork_wing_wall_draft_details')
                ->where('rd_cdwork_cd', '=', $id)
                ->select(
                    'wing_wall_sr_no',
                    'rd_cdwork_cd',
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
        DB::table('asset_road_cdwork_wing_wall_draft_details')
            ->where('rd_cdwork_cd', '=', $id)
            ->delete();
    }

    public function finalizeHeadWallData($culvert_id)
    {
        $id = $culvert_id;
        DB::table('asset_road_cdwork_head_wall_details')->insertUsing([
            'head_wall_sr_no',
            'rd_cdwork_cd',
            'head_wall_type_cd',
            'head_wall_stream_type_cd',
            'head_wall_length',
            'head_wall_width',
            'head_wall_heigth',
            'created_by',
            'created_at',
            'updated_at',
        ], function ($query) use ($id) {
            $query->from('asset_road_cdwork_head_wall_draft_details')
                ->where('rd_cdwork_cd', '=', $id)
                ->select(
                    'head_wall_sr_no',
                    'rd_cdwork_cd',
                    'head_wall_type_cd',
                    'head_wall_stream_type_cd',
                    'head_wall_length',
                    'top_width',
                    'head_wall_heigth',
                    'created_by',
                    'created_at',
                    'updated_at',
                );
        });

        DB::table('asset_road_cdwork_head_wall_draft_details')
            ->where('rd_cdwork_cd', '=', $id)
            ->delete();
    }
}
