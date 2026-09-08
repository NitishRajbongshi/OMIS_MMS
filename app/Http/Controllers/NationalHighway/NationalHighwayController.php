<?php

namespace App\Http\Controllers\NationalHighway;

use Exception;
use App\Models\User;
use App\Models\RoadDetail;
use Illuminate\Http\Request;
use App\Models\UserMenuDetail;
use App\Models\Road\AssetRoadDetail;
use App\Models\AssetMasterRdType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\AssetMasterRoadOwner;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetMasterRoadCategory;
use App\Models\AssetRdSystemIdRunningNo;
use App\Models\AssetRoadChainageMapping;
use App\Models\Road\AssetModificationRequestRoadAssets;

class NationalHighwayController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    
    public function index()
    {
        try {
            $r_details = RoadDetail::orderBy('id', 'asc')->get();

            $r_details = $r_details->map(function ($data) {
                $approve_status = AssetModificationRequestRoadAssets::select('request_id')->where('modification_request_status', 'N')->get()->count();
                $data->approve_status = $approve_status;
                return $data;
            });
            $user = Auth::user();
            $userMapping = DB::table('asset_user_mappings')
                ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd')
                ->where('user_id', '=', $user->id)
                ->get()->first();
            $userid = $user->id;
            $userName = $user->name;
            $userRoleId = User::select('user_role_id')->where('id', $userid)->value('user_role_id');

            $roleData = DB::table('role_details')->join('user_role_details', 'role_details.id', '=', 'user_role_details.role_id')
                ->select('role_details.*')->where('user_role_details.user_id', $userid)->get();
            $inserted = 0;
            $viewed = 0;
            $deleted = 0;
            $updated = 0;
            $freeze = 0;
            foreach ($roleData as $rrr) {
                $ins = $rrr->inserted;
                $vie = $rrr->viewed;
                $del = $rrr->deleted;
                $upd = $rrr->updated;
                $frz = $rrr->freeze_data;

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
                if ($frz == 1) {
                    $freeze = 1;
                }
            }

            $roadReqForUpdate = AssetModificationRequestRoadAssets::select('request_id')->where('modification_request_status', 'N')->get()->count();
            $menus = UserMenuDetail::select('menuid')->where('userid', $userid)->get();
            $menu = $menus->pluck('menuid')->toArray();

            //--- Nitish 08-08-23 start
            // user new table (asset_road_details) for display all road 
            if ($user->office_type_cd == 'SDO') {
                $roadDetails = DB::table('asset_road_chainage_mappings')
                    ->select('asset_road_chainage_mappings.rd_system_id', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to', 'asset_road_chainage_mappings.sub_division_cd', 'asset_road_chainage_mappings.remaining_chainage_length', 'asset_road_chainage_mappings.chainage_created_at_office_cd', 'asset_road_details.rd_name', 'asset_road_details.rd_number', 'asset_road_details.road_length', 'asset_master_road_category.rd_catg_descr', 'asset_master_rd_type.rd_type_descr', 'asset_master_road_owner.owner_name')
                    ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
                    ->join('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->join('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->join('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->whereNotNull('asset_road_chainage_mappings.sub_division_cd')
                    ->where('asset_road_chainage_mappings.remaining_chainage_length', '=', 0)
                    ->where('asset_road_chainage_mappings.sub_division_cd', '=', $userMapping->sub_division_cd)
                    ->get();
            } elseif ($user->office_type_cd == 'DO') {
                $roadDetails = DB::table('asset_road_chainage_mappings')
                    ->select('asset_road_chainage_mappings.rd_system_id', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to', 'asset_road_chainage_mappings.sub_division_cd', 'asset_road_chainage_mappings.remaining_chainage_length', 'asset_road_chainage_mappings.chainage_created_at_office_cd', 'asset_road_details.rd_name', 'asset_road_details.rd_number', 'asset_road_details.road_length', 'asset_master_road_category.rd_catg_descr', 'asset_master_rd_type.rd_type_descr', 'asset_master_road_owner.owner_name')
                    ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
                    ->join('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->join('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->join('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->whereNotNull('asset_road_chainage_mappings.division_cd')
                    ->where('asset_road_chainage_mappings.remaining_chainage_length', '=', 0)
                    ->where('asset_road_chainage_mappings.division_cd', '=', $userMapping->division_cd)
                    ->get();
            } elseif ($user->office_type_cd == 'CO') {
                $roadDetails = DB::table('asset_road_chainage_mappings')
                    ->select('asset_road_chainage_mappings.rd_system_id', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to', 'asset_road_chainage_mappings.sub_division_cd', 'asset_road_chainage_mappings.remaining_chainage_length', 'asset_road_chainage_mappings.chainage_created_at_office_cd', 'asset_road_details.rd_name', 'asset_road_details.rd_number', 'asset_road_details.road_length', 'asset_master_road_category.rd_catg_descr', 'asset_master_rd_type.rd_type_descr', 'asset_master_road_owner.owner_name')
                    ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
                    ->join('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->join('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->join('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->whereNotNull('asset_road_chainage_mappings.circle_cd')
                    ->where('asset_road_chainage_mappings.remaining_chainage_length', '=', 0)
                    ->where('asset_road_chainage_mappings.circle_cd', '=', $userMapping->circle_cd)
                    ->get();
            } elseif ($user->office_type_cd == 'ZO') {
                $roadDetails = DB::table('asset_road_chainage_mappings')
                    ->select('asset_road_chainage_mappings.rd_system_id', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to', 'asset_road_chainage_mappings.sub_division_cd', 'asset_road_chainage_mappings.remaining_chainage_length', 'asset_road_chainage_mappings.chainage_created_at_office_cd', 'asset_road_details.rd_name', 'asset_road_details.rd_number', 'asset_road_details.road_length', 'asset_master_road_category.rd_catg_descr', 'asset_master_rd_type.rd_type_descr', 'asset_master_road_owner.owner_name')
                    ->join('asset_road_details', 'asset_road_chainage_mappings.rd_system_id', '=', 'asset_road_details.rd_system_id')
                    ->join('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->join('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->join('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->whereNotNull('asset_road_chainage_mappings.zone_cd')
                    ->where('asset_road_chainage_mappings.remaining_chainage_length', '=', 0)
                    ->where('asset_road_chainage_mappings.zone_cd', '=', $userMapping->zone_cd)
                    ->get();
            } else {
                $roadDetails = DB::table('asset_road_details')
                    ->select('asset_road_details.*', 'asset_master_road_category.rd_catg_descr', 'asset_master_rd_type.rd_type_descr', 'asset_master_road_owner.owner_name')
                    ->join('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->join('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->join('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }

            return view('nationalHighway.index', compact(
                'user',
                'r_details',
                'inserted',
                'viewed',
                'deleted',
                'updated',
                'freeze',
                'userName',
                'roadReqForUpdate',
                'menu',
                'userRoleId',
                'roadDetails',
            ));
        } catch (Exception $e) {
        }
    }

    public function addHighway(Request $request)
    {
        $user = Auth::user();
        $userid = $user->id;
        $userName = $user->name;
        $userRoleId = User::select('user_role_id')->where('id', $userid)->value('user_role_id');
        $roleData = DB::table('role_details')->join('user_role_details', 'role_details.id', '=', 'user_role_details.role_id')
            ->select('role_details.*')->where('user_role_details.user_id', $userid)->get();

        $reqPending = AssetModificationRequestRoadAssets::select('request_id')->where('modification_request_status', 'N')->get()->count();

        $reqPending = $reqPending->map(function ($senderName) {
            $requester_name = User::where('id', $senderName->request_sent_by)->value('name');
            $senderName->name = $requester_name;
            return $senderName;
        });


        $reqIds = AssetModificationRequestRoadAssets::select('request_id')->where('modification_request_status', 'N')->get()->count();
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

        $roadDetails = AssetRoadDetail::all();
        $roadTypes = AssetMasterRdType::all();
        $roadOwners = AssetMasterRoadOwner::all();
        $roadCategories = AssetMasterRoadCategory::all();

        $roadReqForUpdate = AssetModificationRequestRoadAssets::select('request_id')->where('modification_request_status', 'N')->get()->count();
        $menus = UserMenuDetail::select('menuid')->where('userid', $userid)->get();
        $menu = $menus->pluck('menuid')->toArray();

        return view('nationalHighway.addHighway', compact(
            'user',
            'inserted',
            'viewed',
            'deleted',
            'updated',
            'userName',
            'reqPending',
            'roadReqForUpdate',
            'reqName',
            'menu',
            'userRoleId',
            'roadTypes',
            'roadOwners',
            'roadCategories',
            'roadDetails',
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $userMappingDetails = DB::table('asset_user_mappings')
            ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd')
            ->where('user_id', '=', $user->id)
            ->get()->first();

        // $validateData = $request->validate([
        //     'highway_name' => 'required|string|max:100',
        //     'highway_category' => 'required|string|max:10',
        //     'highway_type' => 'required|string|max:10',
        //     'highway_number' => 'required|string|max:100',
        //     'highway_length' => 'required|numeric|regex:/^\d+(\.\d{0,3})?$/',
        //     'highway_owner' => 'required|string|max:10',
        //     'is_core_network' => 'required|in:Y,N',
        // ]);

        // creating a unique road system id
        $system_id = DB::table('asset_rd_system_id_running_no')
            ->select('*')
            ->where('user_type', '=', 'P')
            ->get()->first();

        $current_number = $system_id->current_running_no;
        $cur_running_number = str_pad($current_number, 4, '0', STR_PAD_LEFT);
        $is_expired = $system_id->expired;
        $user = Auth::user();

        if ($is_expired == 'N' && $current_number < 999) {
            // Check if the road_system_id already exists in the database
            $road_system_id = $cur_running_number;
            $existingRecord = AssetRoadDetail::where('rd_system_id', $road_system_id)->first();

            if ($existingRecord) {
                return redirect()
                    ->route('addNationalHighway')
                    ->with('failed', 'Highway ID is already exist!');
            }

            $highwayData = [
                'rd_system_id' => $road_system_id,
                'rd_category_cd' => $request->road_category,
                'rd_number' => $request->road_number,
                'rd_name' => $request->road_name,
                'rd_type_cd' => $request->road_type,
                'road_length' => $request->road_length,
                'rd_owner_cd' => $request->road_owner,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'road_created_at_office_type' => $user->office_type_cd,
                'road_created_at_office_cd' => $user->office
            ];

            $status = AssetRoadDetail::create($highwayData);

            if ($status) {
                // updating the system id
                $newSystemId = [
                    'current_running_no' => $current_number + 1,
                    'expired' => 'N'
                ];

                $record = AssetRdSystemIdRunningNo::find('P');
                $record->fill($newSystemId);
                $record->save();
                return redirect()
                    ->route('road.add-road')
                    ->with('success', 'Highway added successfully with Highway ID: ' . $road_system_id);
            }
        } else {
            return redirect()
                ->route('road.add-road')
                ->with('failed', 'Current running number exceed the limit');
        }
    }
}
