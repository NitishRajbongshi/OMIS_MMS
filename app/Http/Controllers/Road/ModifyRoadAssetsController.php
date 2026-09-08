<?php

namespace App\Http\Controllers\Road;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\MyHelper;
use App\Models\RoadDetail;
use Illuminate\Http\Request;
use App\Models\UserMenuDetail;
use App\Models\AssetMasterRdType;
use Illuminate\Support\Facades\DB;
use App\Models\AssetMasterDeckType;
use App\Models\AssetMasterPileType;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\AssetMasterRoadOwner;
use App\Models\Road\AssetRoadDetail;
use App\Models\TempRoadModifyDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetMasterBridgeType;
use App\Models\AssetMasterLgdDistrict;
use App\Models\AssetMasterAbutmentType;
use App\Models\AssetMasterRoadCategory;
use App\Models\AssetMasterRdCdWorksType;
use App\Models\AssetMasterRoadCondition;
use App\Models\AssetMasterExpansionJoint;
use App\Models\AssetMasterFoundationType;
use Illuminate\Support\Facades\Validator;
use App\Models\AssetMasterConstructionType;
use App\Models\AssetMasterSuperStructureType;
use App\Models\Road\Master\AssetMasterWellType;
use App\Models\Road\Bridge\AssetRoadBridgeDetail;
use App\Models\Road\CD_Works\AssetRoadCdworkDetail;
use App\Models\Road\Master\AssetMasterHandrailType;
use App\Models\Road\Master\AssetMasterSafetyApronType;
use App\Models\Road\AssetModificationRequestRoadAssets;

class ModifyRoadAssetsController extends Controller
{
    public function __construct()
    {

        $this->middleware("auth");
    }
    public function GetAllRoads()
    {
        try {
            DB::enableQueryLog();
            // $r_details = AssetRoadDetail::orderBy('rd_system_id', 'asc')->get();
            $userid = Auth::user()->id;
            $userName = User::select('name')->where('id', $userid)->value('name');
            $userRoleId = User::select('user_role_id')->where('id', $userid)->value('user_role_id');

            $roleData = DB::table('role_details')->join('user_role_details', 'role_details.id', '=', 'user_role_details.role_id')
                ->select('role_details.*')->where('user_role_details.user_id', $userid)->get();

            $inserted = 0;
            $viewed = 0;
            $deleted = 0;
            $updated = 0;
            foreach ($roleData as $rrr) {
                $ins = $rrr->inserted;
                $vie = $rrr->viewed;
                $del = $rrr->deleted;
                $upd = $rrr->updated;

                if ($ins == 1) {
                    $inserted = 1;
                }
                if ($vie == 1) {
                    $viewed = 1;
                }
                if ($del == 1) {
                    $deleted = 1;
                }
                if ($upd == 1) {
                    $updated = 1;
                }
            }

            $roadReqForUpdate = AssetModificationRequestRoadAssets::select('requested_by')->where('modification_request_status', 'N')->get()->count();
            $menus = UserMenuDetail::select('menuid')->where('userid', $userid)->get();
            $menu = $menus->pluck('menuid')->toArray();


            // fetch All Roads to Modify -- Start
            $user = Auth::user();
            $userMapping = DB::table('asset_user_mappings')
                ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd', 'office_type_cd', 'office_cd')
                ->where('user_id', '=', $user->id)
                ->get()->first();

            $roadTypes = AssetMasterRdType::all();
            $roadOwners = AssetMasterRoadOwner::all();
            $roadCategories = AssetMasterRoadCategory::all();
            $districts = AssetMasterLgdDistrict::all();
            // $block = AssetMasterLgdDistrict::all();

            $roadType = 'SR';
            if ($user->department == '3') {
                $roadType = 'NH';
            }

            $r_details = DB::table('asset_road_details AS tbl_rd')
                ->select(
                    "tbl_rd.rd_system_id",
                    "tbl_rd.rd_number",
                    "tbl_rd.rd_name",
                    "tbl_rd.road_length",
                    "tbl_rd.district_name",
                    "tbl_rd.block_name",
                    "tbl_rd.lat",
                    "tbl_rd.lng",
                    "tbl_rd.rd_category_cd",
                    "tbl_rd.rd_type_cd",
                    "tbl_rd.rd_owner_cd",
                    "rd_catg.rd_catg_descr",
                    "rd_owner.owner_name",
                    "rd_type.rd_type_descr"
                )
                // ->join("asset_road_chainage_mappings AS chng", "chng.rd_system_id", "=", "tbl_rd.rd_system_id")
                ->join('asset_master_road_category AS rd_catg', 'tbl_rd.rd_category_cd', '=', 'rd_catg.rd_catg_cd')
                ->join('asset_master_rd_type AS rd_type', 'tbl_rd.rd_type_cd', '=', 'rd_type.rd_type_cd')
                ->join('asset_master_road_owner AS rd_owner', 'tbl_rd.rd_owner_cd', '=', 'rd_owner.owner_cd')
                ->where('tbl_rd.road_type', '=', $roadType)
                ->where('tbl_rd.road_created_at_office_type', '=', $userMapping->office_type_cd)
                ->where('tbl_rd.road_created_at_office_cd', '=', $userMapping->office_cd)
                ->get();

            $query = DB::getQueryLog();
            Log::info(end($query));

            return view(
                'road.requestRoadAssetModify',
                compact(
                    'r_details',
                    'inserted',
                    'viewed',
                    'deleted',
                    'updated',
                    'userName',
                    'roadReqForUpdate',
                    'menu',
                    'userRoleId',
                    'roadCategories',
                    'roadTypes',
                    'roadOwners',
                    'districts'
                )
            );
        } catch (Exception $e) {
            Log::error("Error Is: ");
            Log::error($e->getMessage());
        }
    }

    // public function getcdworkcd(Request $request)
    // {
    //     $rd_system_id_to_modify = $request->id;
    //     session(['rd_system_id_to_modify' => $rd_system_id_to_modify]);
    //     return redirect('listFinalisedCDWorksForRoad');
    // }
    public function handleDataModifyReqOnARoad(Request $request)
    {
        $rd_system_id_to_modify = $request->id;
        $sub_asset_name = $request->sub_asset_name;
        session(['rd_system_id_to_modify' => $rd_system_id_to_modify]);
        session(['sub_asset_name' => $sub_asset_name]);
        return redirect()->route('GetAllCDWorksForRoad');
    }
    public function listFinalisedCDWorksForRoad()
    {
        try {
            $rd_system_id_to_modify = session('rd_system_id_to_modify');
            $sub_asset_name = session('sub_asset_name');
            $subAssetList = null;
            if ($sub_asset_name == "CDWORK") {
                $culvertTypeMaster = AssetMasterRdCdWorksType::all();
                $conditionMaster = AssetMasterRoadCondition::all();

                $subAssetList = DB::table("asset_road_cdwork_details AS cdwork")
                    ->join("asset_master_rd_cdworks_type AS clvrt_type", "clvrt_type.cdwork_cd", "=", "cdwork.culvert_type_cd")
                    ->join("asset_master_road_condition AS rd_cond", "rd_cond.rd_condition_cd", "=", "cdwork.cdwork_condition")
                    ->select(
                        "cdwork.rd_cdwork_cd",
                        "cdwork.rd_system_id",
                        "cdwork.culvert_no",
                        "cdwork.chainage",
                        "cdwork.culvert_type_cd",
                        "clvrt_type.cdwoerk_descr",
                        "cdwork.cussion",
                        "cdwork.cdwork_size",
                        "cdwork.cdwork_width",
                        "cdwork.cdwork_height",
                        "cdwork.cdwork_length",
                        "cdwork.cdwork_outlet",
                        "cdwork.cdwork_no_of_vents",
                        "cdwork.cdwork_thickness_side_wall",
                        "cdwork.cdwork_thickness_top_slab",
                        "cdwork.cdwork_thickness_bottom_slab",
                        "cdwork.cdwork_has_safety_apron",
                        "cdwork.cdwork_apron_width",
                        "cdwork.cdwork_condition",
                        "rd_cond.rd_condition_descr",
                        "cdwork.discharge",
                        "cdwork.year_of_construction",
                        "cdwork.year_of_rehabilitation",
                        "cdwork.span",
                        "cdwork.carriage_way",
                        "cdwork.no_of_rows",
                        "cdwork.pipe_diameter",
                        "cdwork.pipe_length",
                        "cdwork.pipe_specification",
                        "cdwork.vent_height",
                        "cdwork.no_of_wing_wall",
                        "cdwork.width_each_cell",
                        "cdwork.heigth_each_cell",
                        "cdwork.slab_thickness",
                        "cdwork.height_of_earth_cushion",
                        "cdwork.no_of_cell",
                        "cdwork.culvert_location",
                        "cdwork.no_of_opening",
                        "cdwork.outlet_type_cd",
                        "cdwork.face_wall_size",
                        "cdwork.catch_pit_availability",
                        "cdwork.catch_pit_type_cd",
                        "cdwork.catch_pit_size",
                        "cdwork.catch_pit_condition",
                        "cdwork.catch_toe_wall_size",
                        "cdwork.slab_length",
                        "cdwork.slab_width",
                        "cdwork.cdwork_safety_apron_type",
                        "cdwork.cdwork_safety_apron_hand_rail_type"
                    )
                    ->where("rd_system_id", $rd_system_id_to_modify)->get();

                return view(
                    'road.listFinalisedSubAssetDataOfRoad',
                    compact(
                        'subAssetList',
                        'culvertTypeMaster',
                        'conditionMaster',
                        'sub_asset_name'
                    )
                );
            }

            if ($sub_asset_name == "BRIDGE") {
                $bridgeTypeMaster = AssetMasterBridgeType::all();
                $constTypeMaster = AssetMasterConstructionType::all();
                $foundationTypesMaster = AssetMasterFoundationType::all();
                $abutmentTypeMaster = AssetMasterAbutmentType::all();
                $superStuctureTypeMaster = AssetMasterSuperStructureType::all();
                $handRailTypeMaster = AssetMasterHandrailType::all();
                $deckTypeMaster = AssetMasterDeckType::all();
                $expnJointTypeMaster = AssetMasterExpansionJoint::all();
                $conditionMaster = AssetMasterRoadCondition::all();
                $pileTypeMaster = AssetMasterPileType::all();
                $wellTypeMaster = AssetMasterWellType::all();
                $safetyApronTypeMaster = AssetMasterSafetyApronType::all();

                $subAssetList = DB::table("asset_road_bridge_details AS brdg")
                    ->join("asset_master_bridge_type AS brdg_type", "brdg_type.bridge_type_cd", "=", "brdg.bridge_type_cd")
                    ->leftJoin("asset_master_construction_types AS const_type", "const_type.construction_type_cd", "=", "brdg.construction_type_cd")
                    ->leftJoin("asset_master_foundation_types AS fndn_type", "fndn_type.foundation_cd", "=", "brdg.foundation_type_cd")
                    ->leftJoin("asset_master_abutment_types AS abt_type", "abt_type.abutment_type_cd", "=", "brdg.abutment_type_cd")
                    ->leftJoin("asset_master_super_structure_types AS sup_strctr_type", "sup_strctr_type.st_type_cd", "=", "brdg.super_structure_type_cd")
                    ->leftJoin("asset_master_handrail_types AS hd_rail_type", "hd_rail_type.hand_rail_type_cd", "=", "brdg.handrail_type_cd")
                    ->leftJoin("asset_master_deck_types AS dk_type", "dk_type.deck_type_cd", "=", "brdg.deck_type_cd")
                    ->leftJoin("asset_master_expansion_joints AS exp_join", "exp_join.expn_joint_cd", "=", "brdg.expansion_join_cd")
                    ->leftJoin("asset_master_road_condition AS brg_cond", "brg_cond.rd_condition_cd", "=", "brdg.bridge_condition")
                    ->leftJoin("asset_master_pile_types AS pile_tp", "pile_tp.pile_type_cd", "=", "brdg.pile_type")
                    ->leftJoin("asset_master_well_types AS well_tp", "well_tp.well_type_cd", "=", "brdg.well_type")
                    ->leftJoin("asset_master_safety_apron_types AS sfty_tp", "sfty_tp.apron_type_cd", "=", "brdg.safety_apron_type")
                    ->select(
                        "brdg.rd_bridge_cd",
                        "brdg.rd_system_id",
                        "brdg.bridge_type_cd",
                        "brdg_type.bridge_type_descr",
                        "brdg.bridge_name",
                        "brdg.chainage",
                        "brdg.bridge_lane",
                        "brdg.river_name",
                        "brdg.cd_bridge_length",
                        "brdg.construction_type_cd",
                        "const_type.construction_type_descr",
                        "brdg.year_of_construction",
                        "brdg.no_of_span",
                        "brdg.span_length",
                        "brdg.kerb_distance",
                        "brdg.foundation_type_cd",
                        "fndn_type.foundation_descr",
                        "brdg.year_of_rehabilitation",
                        "brdg.no_of_piers",
                        "brdg.pier_size",
                        "brdg.abutment_type_cd",
                        "abt_type.abutment_type_descr",
                        "brdg.super_structure_type_cd",
                        "sup_strctr_type.st_type_descr",
                        "brdg.handrail_type_cd",
                        "hd_rail_type.hand_rail_type_descr",
                        "brdg.deck_type_cd",
                        "dk_type.deck_type_descr",
                        "brdg.carriage_width",
                        "brdg.guard_stone",
                        "brdg.load_capacity",
                        "brdg.signs",
                        "brdg.lowest_water_level",
                        "brdg.highest_flood_level",
                        "brdg.rfl",
                        "brdg.source_depth",
                        "brdg.discharge",
                        "brdg.deck_level",
                        "brdg.footh_path",
                        "brdg.bearings",
                        "brdg.expansion_join_cd",
                        "exp_join.expn_joint_descr",
                        "brdg.bridge_condition",
                        "brg_cond.rd_condition_descr",
                        "brdg.next_schedule_inspection_date",
                        "brdg.bridge_number",
                        "brdg.bridge_location",
                        "brdg.date_of_last_inspection",
                        "brdg.kerb_width",
                        "brdg.minimum_water_level",
                        "brdg.pile_diameter",
                        "brdg.pile_length",
                        "brdg.pile_type",
                        "pile_tp.pile_type_descr",
                        "brdg.well_type",
                        "well_tp.well_type_descr",
                        "brdg.open_foundation_size",
                        "brdg.depth_open_foundation_size",
                        "brdg.bridge_width",
                        "brdg.has_safety_apron",
                        "brdg.safety_apron_type",
                        "sfty_tp.apron_type_descr",
                        "brdg.apron_width",
                        "brdg.kerb_height"
                    )
                    ->where("rd_system_id", $rd_system_id_to_modify)->get();

                return view(
                    'road.listFinalisedSubAssetDataOfRoad',
                    compact(
                        'subAssetList',
                        'bridgeTypeMaster',
                        'constTypeMaster',
                        'foundationTypesMaster',
                        'conditionMaster',
                        'abutmentTypeMaster',
                        'superStuctureTypeMaster',
                        'handRailTypeMaster',
                        'deckTypeMaster',
                        'expnJointTypeMaster',
                        'conditionMaster',
                        'pileTypeMaster',
                        'wellTypeMaster',
                        'safetyApronTypeMaster',
                        'sub_asset_name'
                    )
                );
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
    }


    public function reqEditRoadAssets(Request $request)
    {
        try {
            DB::enableQueryLog();
            $status = false;
            $req_id = null;
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'modification_req_reason' => 'required|string|max:255',
                    'new_value_cd' => 'required|string|max:255'
                ], [
                    'modification_req_reason.required' => 'This field is required',
                    'new_value_cd.required' => 'This field is required'
                ]);
                if ($validator->fails()) {

                    return response()->json([
                        'message' => 'validationFails',
                        'error' => $validator->errors()
                    ]);
                } else {

                    $new_value_descr = $request->new_value_cd;
                    if ($request->req_table_name == 'asset_road_details') {
                        if ($request->table_field_name == 'rd_catg_cd')
                            $new_value_descr = AssetMasterRoadCategory::select('rd_catg_descr')
                                ->where('rd_catg_cd', $request->new_value_cd)->value('rd_catg_descr');
                    }

                    if ($request->req_table_name == 'asset_road_cdwork_details') {
                        if ($request->table_field_name == 'culvert_type_cd')
                            $new_value_descr = AssetMasterRdCdWorksType::select('cdwoerk_descr')
                                ->where('cdwork_cd', $request->new_value_cd)->value('cdwoerk_descr');
                        if ($request->table_field_name == 'cussion')
                            if ($request->new_value_cd = "Y")
                                $new_value_descr = "Yes";
                            else
                                $new_value_descr = "No";
                        if ($request->table_field_name == 'cdwork_condition')
                            $new_value_descr = AssetMasterRoadCondition::select('rd_condition_descr')
                                ->where('rd_condition_cd', $request->new_value_cd)->value('rd_condition_descr');
                    }

                    if ($request->req_table_name == 'asset_road_bridge_details') {
                        if ($request->table_field_name == 'bridge_type_cd') {
                            $new_value_descr = AssetMasterBridgeType::select('bridge_type_descr')
                                ->where('bridge_type_cd', $request->new_value_cd)->value('bridge_type_descr');
                        }
                        if ($request->table_field_name == 'safety_apron_type') {
                            $new_value_descr = AssetMasterSafetyApronType::select('apron_type_descr')
                                ->where('apron_type_cd', $request->new_value_cd)->value('apron_type_descr');
                        }
                        if ($request->table_field_name == 'well_type') {
                            $new_value_descr = AssetMasterWellType::select('well_type_descr')
                                ->where('well_type_cd', $request->new_value_cd)->value('well_type_descr');
                        }
                        if ($request->table_field_name == 'pile_type') {
                            $new_value_descr = AssetMasterSafetyApronType::select('pile_type_descr')
                                ->where('pile_type_cd', $request->new_value_cd)->value('pile_type_descr');
                        }
                        if ($request->table_field_name == 'bridge_condition') {
                            $new_value_descr = AssetMasterRoadCondition::select('rd_condition_descr')
                                ->where('rd_condition_cd', $request->new_value_cd)->value('rd_condition_descr');
                        }
                        if ($request->table_field_name == 'expansion_join_cd') {
                            $new_value_descr = AssetMasterExpansionJoint::select('expn_joint_descr')
                                ->where('expn_joint_cd', $request->new_value_cd)->value('expn_joint_descr');
                        }
                        if ($request->table_field_name == 'deck_type_cd') {
                            $new_value_descr = AssetMasterDeckType::select('deck_type_descr')
                                ->where('deck_type_cd', $request->new_value_cd)->value('deck_type_descr');
                        }
                        if ($request->table_field_name == 'handrail_type_cd') {
                            $new_value_descr = AssetMasterSafetyApronType::select('hand_rail_type_descr')
                                ->where('hand_rail_type_cd', $request->new_value_cd)->value('hand_rail_type_descr');
                        }
                        if ($request->table_field_name == 'super_structure_type_cd') {
                            $new_value_descr = AssetMasterSafetyApronType::select('st_type_descr')
                                ->where('st_type_cd', $request->new_value_cd)->value('st_type_descr');
                        }

                        if ($request->table_field_name == 'abutment_type_cd') {
                            $new_value_descr = AssetMasterSafetyApronType::select('abutment_type_descr')
                                ->where('abutment_type_cd', $request->new_value_cd)->value('abutment_type_descr');
                        }

                        if ($request->table_field_name == 'foundation_type_cd') {
                            $new_value_descr = AssetMasterSafetyApronType::select('foundation_descr')
                                ->where('foundation_cd', $request->new_value_cd)->value('foundation_descr');
                        }

                        if ($request->table_field_name == 'construction_type_cd') {
                            $new_value_descr = AssetMasterSafetyApronType::select('construction_type_descr')
                                ->where('construction_type_cd', $request->new_value_cd)->value('construction_type_descr');
                        }

                        if ($request->table_field_name == 'aaaaa') {
                            $new_value_descr = AssetMasterSafetyApronType::select('xxxx')
                                ->where('zzzzz', $request->new_value_cd)->value('xxxxx');
                        }
                    }
                    $req_dtls[] = [
                        'table_name' => $request->req_table_name,
                        'user_field_name' => $request->user_field_name,
                        'table_field_name' => $request->table_field_name,
                        'old_value_cd' => $request->old_value_cd,
                        'old_value_descr' => $request->old_value_descr,
                        'new_value_cd' => $request->new_value_cd,
                        'new_value_descr' => $new_value_descr,
                        'modification_reason' => $request->modification_req_reason,
                        'requested_by' => Auth::user()->id,
                        'requested_on' => date('Y-m-d H:i:s')
                    ];
                    // }
                    // if ($request->req_table_name == 'asset_road_cdwork_details') {
                    // }
                    $reqDtlsJson = ["req_dtls" => $req_dtls];
                    $startDate = date('Y-m-d 00:00:00');
                    $endDate = date('Y-m-d H:i:s');
                    $objExistingAssetModificationRequest = AssetModificationRequestRoadAssets::where('modification_request_status', 'N')
                        ->where('rd_system_id', $request->rd_system_id)
                        ->where('sub_asset_cd', $request->req_sub_asset_cd)
                        ->where('requested_by', Auth::user()->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->get()->first();

                    $query = DB::getQueryLog();
                    Log::info(end($query));
                    Log::info($objExistingAssetModificationRequest);
                    if ($objExistingAssetModificationRequest) {
                        $req_id = $objExistingAssetModificationRequest->request_id;
                        Log::info("Request already exist");
                        $new_req_dtls = [
                            'table_name' => $request->req_table_name,
                            'user_field_name' => $request->user_field_name,
                            'table_field_name' => $request->table_field_name,
                            'old_value_cd' => $request->old_value_cd,
                            'old_value_descr' => $request->old_value_descr,
                            'new_value_cd' => $request->new_value_cd,
                            'new_value_descr' => $new_value_descr,
                            'modification_reason' => $request->modification_req_reason,
                            'requested_by' => Auth::user()->id,
                            'requested_on' => date('Y-m-d H:i:s')
                        ];
                        $existingRequestsDtlsJson = json_decode($objExistingAssetModificationRequest->modification_request_dtls);
                        $arrExistingReqDtls = $existingRequestsDtlsJson->req_dtls;
                        array_push($arrExistingReqDtls, $new_req_dtls);

                        $reqDtlsJson = ["req_dtls" => $arrExistingReqDtls];
                        // Log::info($reqDtlsJson);
                        $data = AssetModificationRequestRoadAssets::find($objExistingAssetModificationRequest->request_id);
                        $data->modification_request_dtls = json_encode($reqDtlsJson);
                        $status = $data->save();
                    } else {
                        Log::info("New Request");
                        $data = new AssetModificationRequestRoadAssets();
                        $req_id = Auth::user()->id . date('YmdHis');
                        $data->request_id = $req_id;
                        $data->asset_name = $request->req_asset_name;
                        $data->rd_system_id = $request->rd_system_id;
                        $data->is_sub_asset = $request->req_is_sub_asset;
                        $data->sub_asset_cd = $request->req_sub_asset_cd;
                        $data->modification_request_dtls = json_encode($reqDtlsJson);
                        $data->modification_request_status = "N";
                        $data->requested_by = Auth::user()->id;
                        $data->created_at = Carbon::now();
                        $data->updated_at = Carbon::now();
                        $status = $data->save();
                    }

                    if ($status) {
                        return response()->json([
                            'message' => 'success',
                            'req_id' => $req_id,
                            'field_name' => $request->user_field_name
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'failed'
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("Error Is: ");
            Log::error($e->getMessage());
            return $e;
        }
    }

    public function GetModifyRoad()
    {
        try {
            $userid = Auth::user()->id;
            $userName = User::select('name')->where('id', $userid)->value('name');
            $userRoleId = User::select('user_role_id')->where('id', $userid)->value('user_role_id');

            $roleData = DB::table('role_details')->join('user_role_details', 'role_details.id', '=', 'user_role_details.role_id')
                ->select('role_details.*')->where('user_role_details.user_id', $userid)->get();

            $reqPending = AssetModificationRequestRoadAssets::where('modification_request_status', 'N')->orderBy('created_at', 'asc')->get();

            $reqPending = $reqPending->map(function ($senderName) {
                $requester_name = User::where('id', $senderName->requested_by)->value('name');
                $senderName->name = $requester_name;
                return $senderName;
            });


            $reqIds = AssetModificationRequestRoadAssets::select('requested_by')->where('modification_request_status', 'N')->get()->pluck('requested_by');
            $reqName = [];
            foreach ($reqIds as $rr) {
                $res = User::select('name')->where('id', $rr)->first();
                $reqName[] = $res;
            }


            $inserted = 0;
            $viewed = 0;
            $deleted = 0;
            $updated = 0;
            foreach ($roleData as $rrr) {
                $ins = $rrr->inserted;
                $vie = $rrr->viewed;
                $del = $rrr->deleted;
                $upd = $rrr->updated;

                if ($ins == 1) {
                    $inserted = 1;
                }
                if ($vie == 1) {
                    $viewed = 1;
                }
                if ($del == 1) {
                    $deleted = 1;
                }
                if ($upd == 1) {
                    $updated = 1;
                }
            }

            $roadReqForUpdate = AssetModificationRequestRoadAssets::select('request_id')->where('modification_request_status', 'N')->get()->count();
            $menus = UserMenuDetail::select('menuid')->where('userid', $userid)->get();
            $menu = $menus->pluck('menuid')->toArray();

            return view(
                'road.modifyroad',
                compact(
                    'inserted',
                    'viewed',
                    'deleted',
                    'updated',
                    'userName',
                    'reqPending',
                    'roadReqForUpdate',
                    'reqName',
                    'menu',
                    'userRoleId'
                )
            );
        } catch (Exception $e) {
        }
    }
    public function finalApproveByAdmin(Request $request)
    {
        try {
            $status = false;
            $action = null;
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'txtRemark' => 'required|string|max:255'
                ], [
                    'txtRemark.required' => 'This field is required'
                ]);
                if ($validator->fails()) {

                    return response()->json([
                        'message' => 'validationFails',
                        'error' => $validator->errors()
                    ]);
                }

                Log::info("Action is : " . $request->request_id);
                Log::info("Action is : " . $request->approve_or_reject);
                $csdata = AssetModificationRequestRoadAssets::find($request->request_id);


                if ($request->approve_or_reject == "Approve") {
                    $action = "Approved";
                    Log::info("request Details:  " . $csdata->modification_request_dtls);
                    Log::info("Asset Name:  " . $csdata->asset_name);
                    Log::info("Road System Id:  " . $csdata->rd_system_id);
                    Log::info("is_sub_asset:  " . $csdata->is_sub_asset);
                    Log::info("sub_asset_cd:  " . $csdata->sub_asset_cd);

                    if ($csdata->asset_name == "Road") {
                        $appData = AssetRoadDetail::find($csdata->rd_system_id);
                        $reqDtlsJson = json_decode($csdata->modification_request_dtls);
                        // Log::info("reqDtlsJson:  " . $reqDtlsJson->req_dtls);
                        $reqDtls = $reqDtlsJson->req_dtls;
                        if ($appData) {
                            //Copy the selected data to history table
                            $rd_system_cd = $csdata->rd_system_id;
                            $status_move_data = DB::table('asset_road_details_hist')->insertUsing([
                                'rd_system_id',
                                'rd_category_cd',
                                'rd_number',
                                'rd_name',
                                'rd_type_cd',
                                'road_length',
                                'rd_owner_cd',
                                'road_type',
                                'district_name',
                                'block_name',
                                'lng',
                                'lat',
                                'created_at',
                                'updated_at',
                                'updated_by',
                                'created_by'
                            ], function ($query) use ($rd_system_cd) {
                                $query->from('asset_road_details')
                                    ->where('rd_system_id', '=', $rd_system_cd)
                                    ->select(
                                        'rd_system_id',
                                        'rd_category_cd',
                                        'rd_number',
                                        'rd_name',
                                        'rd_type_cd',
                                        'road_length',
                                        'rd_owner_cd',
                                        'road_type',
                                        'district_name',
                                        'block_name',
                                        'lng',
                                        'lat',
                                        'created_at',
                                        'updated_at',
                                        'updated_by',
                                        'created_by'
                                    );
                            });
                            if ($status_move_data > 0) {
                                Log::info("Road Asset Data copied to History Table with rd_system_cd: " . $rd_system_cd);
                            }

                            //After Cpoying data to History Table , update the current data
                            foreach ($reqDtls as $item) {
                                $table_field_name = $item->table_field_name;
                                $old_value_cd = $item->old_value_cd;
                                $new_value_cd = $item->new_value_cd;

                                $appData->$table_field_name = $new_value_cd;
                            }
                            $status = $appData->save();
                        } else {
                            Log::Info("No Road Data Data found with with road system id: " . $csdata->rd_system_id);
                            return response()->json([
                                'message' => 'failed'
                            ]);
                        }
                    }

                    if ($csdata->asset_name == "CD Works") {
                        $appData = AssetRoadCdworkDetail::find($csdata->sub_asset_cd);
                        Log::info("appData:  " . $appData);
                        $reqDtlsJson = json_decode($csdata->modification_request_dtls);

                        $reqDtls = $reqDtlsJson->req_dtls;
                        Log::info("reqDtls:  " . json_encode($reqDtls));
                        if ($appData) {
                            //Copy the selected data to history table
                            $rd_culvert_cd = $csdata->sub_asset_cd;
                            $status_move_data = DB::table('asset_road_cdwork_details_hist')->insertUsing([
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
                                'no_of_rows',
                                'pipe_diameter',
                                'pipe_length',
                                'pipe_specification',
                                'vent_height',
                                'no_of_wing_wall',
                                'width_each_cell',
                                'heigth_each_cell',
                                'slab_thickness',
                                'height_of_earth_cushion',
                                'no_of_cell',
                                'culvert_chainge_from',
                                'culvert_chainge_to',
                                'culvert_location',
                                'no_of_opening',
                                'outlet_type_cd',
                                'face_wall_size',
                                'catch_pit_availability',
                                'catch_pit_type_cd',
                                'catch_pit_size',
                                'catch_pit_condition',
                                'catch_toe_wall_size',
                                'cdwork_remark',
                                'slab_length',
                                'slab_width',
                                'cdwork_safety_apron_type',
                                'cdwork_safety_apron_hand_rail_type',
                                'created_at',
                                'updated_at',
                                'updated_by',
                                'created_by',
                                'created_at_office_cd'
                            ], function ($query) use ($rd_culvert_cd) {
                                $query->from('asset_road_cdwork_details')
                                    ->where('rd_cdwork_cd', '=', $rd_culvert_cd)
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
                                        'no_of_rows',
                                        'pipe_diameter',
                                        'pipe_length',
                                        'pipe_specification',
                                        'vent_height',
                                        'no_of_wing_wall',
                                        'width_each_cell',
                                        'heigth_each_cell',
                                        'slab_thickness',
                                        'height_of_earth_cushion',
                                        'no_of_cell',
                                        'culvert_chainge_from',
                                        'culvert_chainge_to',
                                        'culvert_location',
                                        'no_of_opening',
                                        'outlet_type_cd',
                                        'face_wall_size',
                                        'catch_pit_availability',
                                        'catch_pit_type_cd',
                                        'catch_pit_size',
                                        'catch_pit_condition',
                                        'catch_toe_wall_size',
                                        'cdwork_remark',
                                        'slab_length',
                                        'slab_width',
                                        'cdwork_safety_apron_type',
                                        'cdwork_safety_apron_hand_rail_type',
                                        'created_at',
                                        'updated_at',
                                        'updated_by',
                                        'created_by',
                                        'created_at_office_cd'
                                    );
                            });
                            if ($status_move_data > 0) {
                                Log::info("Culvert Data copied to History Table with rd_cdwork_cd: " . $rd_culvert_cd);
                            }


                            foreach ($reqDtls as $item) {
                                $table_field_name = $item->table_field_name;
                                $old_value_cd = $item->old_value_cd;
                                $new_value_cd = $item->new_value_cd;
                                Log::info("new_value_cd:  " . $new_value_cd);
                                $appData->$table_field_name = $new_value_cd;
                            }
                            $status = $appData->save();
                        } else {
                            Log::Info("No Culvert Data Data found with culvert_cd: " . $csdata->sub_asset_cd);
                            return response()->json([
                                'message' => 'failed'
                            ]);
                        }
                    }

                    if ($csdata->asset_name == "BRIDGE") {
                        $appData = AssetRoadBridgeDetail::find($csdata->sub_asset_cd);
                        Log::info("appData:  " . $appData);
                        $reqDtlsJson = json_decode($csdata->modification_request_dtls);

                        $reqDtls = $reqDtlsJson->req_dtls;
                        Log::info("reqDtls:  " . json_encode($reqDtls));
                        if ($appData) {
                            //Copy the selected data to history table
                            $uid = Auth::user()->id;
                            $currentTime = now();
                            $rd_bridge_cd = $csdata->sub_asset_cd;
                            $status_move_data = DB::table('asset_road_bridge_details_hist')->insertUsing([
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
                                'foundation_type_cd',
                                'year_of_rehabilitation',
                                'no_of_piers',
                                'pier_size',
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
                                'bearings',
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
                                'pile_diameter',
                                'pile_length',
                                'pile_type',
                                'well_type',
                                'well_size',
                                'open_foundation_size',
                                'depth_open_foundation_size',
                                'bridge_width',
                                'has_head_wall',
                                'has_wing_wall',
                                'has_retain_wall',
                                'has_abutment_wall',
                                'has_safety_apron',
                                'safety_apron_type',
                                'safety_apron_hand_rail_type',
                                'apron_width',
                                'kerb_height',
                                'hist_created_by',
                                'hist_created_on',
                                'hist_remarks'

                            ], function ($query) use ($rd_bridge_cd, $currentTime, $uid) {
                                $query->from('asset_road_bridge_details')
                                    ->where('rd_bridge_cd', '=', $rd_bridge_cd)
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
                                        'kerb_distance',
                                        'foundation_type_cd',
                                        'year_of_rehabilitation',
                                        'no_of_piers',
                                        'pier_size',
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
                                        'bearings',
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
                                        'pile_diameter',
                                        'pile_length',
                                        'pile_type',
                                        'well_type',
                                        'well_size',
                                        'open_foundation_size',
                                        'depth_open_foundation_size',
                                        'bridge_width',
                                        'has_head_wall',
                                        'has_wing_wall',
                                        'has_retain_wall',
                                        'has_abutment_wall',
                                        'has_safety_apron',
                                        'safety_apron_type',
                                        'safety_apron_hand_rail_type',
                                        'apron_width',
                                        'kerb_height',
                                        DB::raw("'$uid' as hist_created_by"),
                                        DB::raw("'$currentTime' as hist_created_on"),
                                        DB::raw("'Modify Request' as hist_remarks")

                                    );
                            });
                            if ($status_move_data > 0) {
                                Log::info("Bridge Data copied to History Table with rd_bridge_cd: " . $rd_bridge_cd);
                            }


                            foreach ($reqDtls as $item) {
                                $table_field_name = $item->table_field_name;
                                $old_value_cd = $item->old_value_cd;
                                $new_value_cd = $item->new_value_cd;
                                Log::info("new_value_cd:  " . $new_value_cd);
                                $appData->$table_field_name = $new_value_cd;
                            }
                            $status = $appData->save();
                        } else {
                            Log::Info("No Bridge Data Data found with bridge_cd: " . $csdata->sub_asset_cd);
                            return response()->json([
                                'message' => 'failed'
                            ]);
                        }
                    }


                    $csdata->modification_request_status = "A";
                    $csdata->apprv_rejected_by = Auth::user()->id;
                    $csdata->remark_apprv_reject = $request->txtRemark;
                    $csdata->apprv_rejected_at = Carbon::now();
                    $csdata->save();
                }
                if ($request->approve_or_reject == "Reject") {
                    $action = "Rejected";
                    $csdata->modification_request_status = "R";
                    $csdata->apprv_rejected_by = Auth::user()->id;
                    $csdata->remark_apprv_reject = $request->txtRemark;
                    $csdata->apprv_rejected_at = Carbon::now();
                    $status = $csdata->save();
                }


                // $approveData = RoadDetail::where('rd_system_id', $csdata->rd_system_id)->first();
                // $appData->save();
                // print_r($approveData->RNo); die();

                if ($status) {
                    return response()->json([
                        'message' => 'success',
                        'action' => $action
                    ]);
                } else {
                    return response()->json([
                        'message' => 'failed'
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error("Error In Final Approval is : " . $e->getMessage());
            // DB::rollBack();
            return response()->json([
                'message' => 'fail'
            ]);
        }
    }
}
