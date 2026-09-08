<?php

namespace App\Http\Controllers\MIS;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Road\AssetRoadDistressDetails;
use App\Models\AssetMasterZone;
use App\Models\AssetMasterCircle;
use App\Models\AssetMasterDivision;
use App\Models\AssetMasterDistressType;


class MISDistressController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function getAllActiveRoadDistresses()
    {
        try {
            DB::enableQueryLog();
            // $r_details = AssetRoadDetail::orderBy('rd_system_id', 'asc')->get();
            $userid = Auth::user()->id;
            $userName = User::select('name')->where('id', $userid)->value('name');
            $userRoleId = User::select('user_role_id')->where('id', $userid)->value('user_role_id');
            $roleData = DB::table('role_details')->join('user_role_details', 'role_details.id', '=', 'user_role_details.role_id')
                ->select('role_details.*')->where('user_role_details.user_id', $userid)->get();
            // fetch All Roads Distress Data -- Start
            $user = Auth::user();
            $userMapping = DB::table('asset_user_mappings')
                ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd', 'office_type_cd', 'office_cd')
                ->where('user_id', '=', $user->id)
                ->get()->first();
            // $roadTypes = AssetMasterRdType::all();
            // $roadOwners = AssetMasterRoadOwner::all();
            // $roadCategories = AssetMasterRoadCategory::all();
            // $districts = AssetMasterLgdDistrict::all();
            // $block = AssetMasterLgdDistrict::all();
            $distrs_dtls = DB::table('asset_road_distress_details AS rd_distrs')
                ->select(
                    "rd_distrs.rd_distress_cd",
                    "rd_distrs.rd_system_id",
                    "rd_distrs.start_lat",
                    "rd_distrs.start_lon",
                    "rd_distrs.end_lat",
                    "rd_distrs.end_lon",
                    "rd_distrs.distress_type_cd",
                    "distres_type.distress_type_descr",
                    "rd_distrs.days_to_restore",
                    "rd_distrs.date_of_occurance",
                    "rd_distrs.distress_remarks",
                    "rd_distrs.restored_status",
                    "rd_distrs.distress_length_in_km",
                    "rd_distrs.start_landmark",
                    "rd_distrs.end_landmark",
                    "rd_dlts.rd_name",
                    "rd_dlts.rd_number",
                    "rd_dlts.district_name",
                    "rd_dlts.block_name",
                    "rd_dlts.division_name"
                )
                ->join('asset_road_details AS rd_dlts', 'rd_dlts.rd_system_id', '=', 'rd_distrs.rd_system_id')
                ->join('asset_master_distress_type AS distres_type', 'distres_type.distress_type_cd', '=', 'rd_distrs.distress_type_cd')
                ->where('rd_distrs.restored_status', '=', 'N')
                ->orderBy('rd_distrs.date_of_occurance', 'desc')
                ->orderBy('rd_dlts.district_name', 'asc')
                ->orderBy('rd_dlts.block_name', 'asc')
                ->get();
            $query = DB::getQueryLog();
            Log::info(end($query));
            $zoneDetails = AssetMasterZone::where('dept_cd', '14')->get();
            $circleDetails = AssetMasterCircle::where('dept_cd', '14')->get();
            $divisionDetails = AssetMasterDivision::where('dept_cd', '14')->orderBy('division_name', "asc")->get();
            $distressTypes = AssetMasterDistressType::orderBy('distress_type_descr', 'asc')->get();
        } catch (Exception $e) {
            Log::error("Error in getAllActiveRoadDistresses Data: " . $e->getMessage());
        }
        return view(
            'mis.misRoadDistress',
            compact(
                'distrs_dtls',
                'zoneDetails',
                'circleDetails',
                'divisionDetails',
                'distressTypes'
            )
        );
    }

    public function filterRoadDistresses(Request $request)
    {
        try {
            $division_name = $request->division;
            $distress_type = $request->distressType;
            $restore_status = $request->restore_status;
            $distress_fr = $request->distres_from_date;
            $distress_to = $request->distres_to_date;
            DB::enableQueryLog();
            // $r_details = AssetRoadDetail::orderBy('rd_system_id', 'asc')->get();
            $userid = Auth::user()->id;
            $userName = User::select('name')->where('id', $userid)->value('name');
            $userRoleId = User::select('user_role_id')->where('id', $userid)->value('user_role_id');

            $roleData = DB::table('role_details')->join('user_role_details', 'role_details.id', '=', 'user_role_details.role_id')
                ->select('role_details.*')->where('user_role_details.user_id', $userid)->get();

            // fetch All Roads Distress Data -- Start
            $user = Auth::user();
            $userMapping = DB::table('asset_user_mappings')
                ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd', 'office_type_cd', 'office_cd')
                ->where('user_id', '=', $user->id)
                ->get()->first();

            // $roadTypes = AssetMasterRdType::all();
            // $roadOwners = AssetMasterRoadOwner::all();
            // $roadCategories = AssetMasterRoadCategory::all();
            // $districts = AssetMasterLgdDistrict::all();
            // $block = AssetMasterLgdDistrict::all();

            $base_query = DB::table('asset_road_distress_details AS rd_distrs')
                ->select(
                    "rd_distrs.rd_distress_cd",
                    "rd_distrs.rd_system_id",
                    "rd_distrs.start_lat",
                    "rd_distrs.start_lon",
                    "rd_distrs.end_lat",
                    "rd_distrs.end_lon",
                    "rd_distrs.distress_type_cd",
                    "distres_type.distress_type_descr",
                    "rd_distrs.days_to_restore",
                    "rd_distrs.date_of_occurance",
                    "rd_distrs.distress_remarks",
                    "rd_distrs.restored_status",
                    "rd_distrs.distress_length_in_km",
                    "rd_distrs.start_landmark",
                    "rd_distrs.end_landmark",
                    "rd_dlts.rd_name",
                    "rd_dlts.rd_number",
                    "rd_dlts.district_name",
                    "rd_dlts.block_name",
                    "rd_dlts.division_name"
                )
                ->join('asset_road_details AS rd_dlts', 'rd_dlts.rd_system_id', '=', 'rd_distrs.rd_system_id')
                ->join('asset_master_distress_type AS distres_type', 'distres_type.distress_type_cd', '=', 'rd_distrs.distress_type_cd')
                ->where('rd_distrs.restored_status', '=', 'N')
                ->orderBy('rd_distrs.date_of_occurance', 'desc')
                ->orderBy('rd_dlts.district_name', 'asc')
                ->orderBy('rd_dlts.block_name', 'asc');
            if ($division_name != "A")
                $base_query = $base_query->where(DB::raw('upper(rd_dlts.division_name)'), 'ilike', "%{$division_name}%");

            if ($distress_type != 'A')
                $base_query = $base_query->where('rd_distrs.distress_type_cd', $distress_type);

            if ($restore_status != 'A')
                $base_query = $base_query->where('rd_distrs.restored_status', $restore_status);
            Log::info("distress_fr : " . $distress_fr);
            Log::info("distress_to : " . $distress_to);
            if ($distress_fr != "" && $distress_to != "")
                $base_query = $base_query->whereBetween('rd_distrs.date_of_occurance', [$distress_fr, $distress_to]);
            $distrs_dtls = $base_query->get();
            $query = DB::getQueryLog();
            Log::info(end($query));
            $zoneDetails = AssetMasterZone::where('dept_cd', '14')->get();
            $circleDetails = AssetMasterCircle::where('dept_cd', '14')->get();
            $divisionDetails = AssetMasterDivision::where('dept_cd', '14')->orderBy('division_name', "asc")->get();
            $distressTypes = AssetMasterDistressType::orderBy('distress_type_descr', 'asc')->get();
        } catch (Exception $e) {
            Log::error("Error in Filter Distress Data: " . $e->getMessage());
        }
        return view(
            'mis.misRoadDistress',
            compact(
                'distrs_dtls',
                'zoneDetails',
                'circleDetails',
                'divisionDetails',
                'distressTypes'
            )
        );
    }

    public function listActiveRoadDistressesToUpdate()
    {
        try {
            DB::enableQueryLog();
            $userid = Auth::user()->id;
            $userName = User::select('name')->where('id', $userid)->value('name');
            $userRoleId = User::select('user_role_id')->where('id', $userid)->value('user_role_id');
            $roleData = DB::table('role_details')->join('user_role_details', 'role_details.id', '=', 'user_role_details.role_id')
                ->select('role_details.*')->where('user_role_details.user_id', $userid)->get();

            // fetch All Roads Distress Data -- Start
            $user = Auth::user();
            $userMapping = DB::table('asset_user_mappings')
                ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd', 'office_type_cd', 'office_cd')
                ->where('user_id', '=', $user->id)
                ->get()->first();

            // $roadTypes = AssetMasterRdType::all();
            // $roadOwners = AssetMasterRoadOwner::all();
            // $roadCategories = AssetMasterRoadCategory::all();
            // $districts = AssetMasterLgdDistrict::all();
            // $block = AssetMasterLgdDistrict::all();

            $distrs_dtls = DB::table('asset_road_distress_details AS rd_distrs')
                ->select(
                    "rd_distrs.rd_distress_cd",
                    "rd_distrs.rd_system_id",
                    "rd_distrs.start_lat",
                    "rd_distrs.start_lon",
                    "rd_distrs.end_lat",
                    "rd_distrs.end_lon",
                    "rd_distrs.distress_type_cd",
                    "distres_type.distress_type_descr",
                    "rd_distrs.days_to_restore",
                    "rd_distrs.date_of_occurance",
                    "rd_distrs.distress_remarks",
                    "rd_distrs.restored_status",
                    "rd_distrs.distress_length_in_km",
                    "rd_distrs.start_landmark",
                    "rd_distrs.end_landmark",
                    "rd_dlts.rd_name",
                    "rd_dlts.rd_number",
                    "rd_dlts.district_name",
                    "rd_dlts.block_name",
                    "rd_dlts.division_name"

                )
                ->join('asset_road_details AS rd_dlts', 'rd_dlts.rd_system_id', '=', 'rd_distrs.rd_system_id')
                ->join('asset_master_distress_type AS distres_type', 'distres_type.distress_type_cd', '=', 'rd_distrs.distress_type_cd')
                ->where('rd_distrs.restored_status', '=', 'N')
                ->orderBy('rd_distrs.date_of_occurance', 'desc')
                ->orderBy('rd_dlts.district_name', 'asc')
                ->orderBy('rd_dlts.block_name', 'asc')
                ->get();
            $query = DB::getQueryLog();
            Log::info(end($query));
        } catch (Exception $e) {
            Log::error("Error in Listing Distress Data to Update: " . $e->getMessage());
        }
        return view(
            'road.listDistressedRoadData',
            compact(
                'distrs_dtls'
            )
        );
    }

    public function updateDistress(Request $request)
    {
        try {
            $status = false;
            $action = null;
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'txt_distress_remark' => 'required|string|max:255',
                    'txt_start_landmark' => 'required|string|max:255',
                    'txt_end_landmark' => 'required|string|max:255',
                    'sel_new_restore_status' => 'required|string|max:10'
                ], [
                    'txt_distress_remark.required' => 'This field is required',
                    'txt_start_landmark.required' => 'This field is required',
                    'txt_end_landmark.required' => 'This field is required',
                    'sel_new_restore_status.required' => 'This field is required'
                ]);
                if ($validator->fails()) {

                    return response()->json([
                        'message' => 'validationFails',
                        'error' => $validator->errors()
                    ]);
                }

                $distrs_data = AssetRoadDistressDetails::find($request->txt_distress_cd);
                // $appData = AssetRoadDetail::find($distrs_data->rd_system_id);
                // Log::info("reqDtlsJson:  " . $reqDtlsJson->req_dtls);
                // $reqDtls = $reqDtlsJson->req_dtls;
                if ($distrs_data) {
                    //Copy the selected data to history table
                    $rd_distress_cd = $distrs_data->rd_distress_cd;
                    $status_move_data = DB::table('asset_road_distress_details_hist')->insertUsing([
                        'rd_distress_cd',
                        'rd_system_id',
                        'start_lat',
                        'start_lon',
                        'end_lat',
                        'end_lon',
                        'distress_type_cd',
                        'days_to_restore',
                        'date_of_occurance',
                        'distress_remarks',
                        'is_published',
                        'restored_status',
                        'restored_on',
                        'restore_status_updated_by',
                        'restoration_remark',
                        'distress_length_in_km',
                        'start_landmark',
                        'end_landmark',
                        'created_by',
                        'created_at',
                        'updated_at'
                    ], function ($query) use ($rd_distress_cd) {
                        $query->from('asset_road_distress_details')
                            ->where('rd_distress_cd', '=', $rd_distress_cd)
                            ->select(
                                'rd_distress_cd',
                                'rd_system_id',
                                'start_lat',
                                'start_lon',
                                'end_lat',
                                'end_lon',
                                'distress_type_cd',
                                'days_to_restore',
                                'date_of_occurance',
                                'distress_remarks',
                                'is_published',
                                'restored_status',
                                'restored_on',
                                'restore_status_updated_by',
                                'restoration_remark',
                                'distress_length_in_km',
                                'start_landmark',
                                'end_landmark',
                                'created_by',
                                'created_at',
                                'updated_at'
                            );
                    });
                    if ($status_move_data > 0) {
                        Log::info("Road Distress Data copied to History Table with rd_distress_cd: " . $rd_distress_cd);
                    }
                } else {
                    Log::Info("No Road Data Data found with with road distress cd: ");
                    return response()->json([
                        'message' => 'failed'
                    ]);
                }
                $distrs_data->restored_status = $request->sel_new_restore_status;
                $distrs_data->start_landmark = $request->txt_start_landmark;
                $distrs_data->end_landmark = $request->txt_end_landmark;
                $distrs_data->days_to_restore = $request->txt_no_days_to_restore;
                $distrs_data->distress_remarks = $request->txt_distress_remark;
                $distrs_data->updated_at = Carbon::now();
                $status = $distrs_data->save();
                if ($status) {
                    return response()->json([
                        'message' => 'success',
                        'distress_cd' => $rd_distress_cd
                    ]);
                } else {
                    return response()->json([
                        'message' => 'failed'
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error("Error In Final Approval is : " . $e->getMessage());
            return response()->json([
                'message' => 'fail'
            ]);
        }
    }
}
