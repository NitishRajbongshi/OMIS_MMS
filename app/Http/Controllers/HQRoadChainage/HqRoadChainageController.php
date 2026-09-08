<?php

namespace App\Http\Controllers\HQRoadChainage;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserMenuDetail;
use App\Models\AssetMasterZone;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetRoadChainageMapping;
use App\Models\Road\AssetModificationRequestRoadAssets;
use Illuminate\Support\Facades\Log;

class HqRoadChainageController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        try {
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

            $roadReqForUpdate = AssetModificationRequestRoadAssets::select('request_id')->where('modification_request_status', 'N')->get()->count();
            $menus = UserMenuDetail::select('menuid')->where('userid', $userid)->get();
            $menu = $menus->pluck('menuid')->toArray();

            //Road Details
            $roadDetails = DB::table('asset_road_details')
                ->select('asset_road_details.rd_system_id', 'asset_road_details.rd_number', 'asset_road_details.rd_name', 'asset_road_details.road_length')
                ->leftJoin('asset_road_chainage_mappings', 'asset_road_details.rd_system_id', '=', 'asset_road_chainage_mappings.rd_system_id')
                ->whereNull('asset_road_chainage_mappings.rd_system_id')
                ->get();

            $zones = AssetMasterZone::all();
            return view('road.chainage.road-chainage-hq', compact(
                'inserted',
                'viewed',
                'deleted',
                'updated',
                'userName',
                'roadReqForUpdate',
                'menu',
                'userRoleId',
                'roadDetails',
                'zones'
            ));
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }

    public function getRoadById(Request $request)
    {
        try {
            if (isset($request->id)) {
                $result = DB::table('asset_road_details')
                    ->select('rd_system_id', 'rd_number', 'rd_name', 'road_length', 'road_type', 'district_name', 'block_name')
                    ->where('rd_system_id', '=', $request->id)
                    ->get()
                    ->first();
                if ($result) {
                    return response()->json([
                        'status' => 'success',
                        'result' => $result
                    ]);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Failed to get road details!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong!'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 'failed',
                'message' => 'Some internal problem occured!'
            ]);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'road_list_dropdown' => 'required',
            'chainage_level' => 'required'
        ]);
        try {
            $data = [
                'rd_system_id' => $request->road_list_dropdown,
                'chainage_from' => 0,
                'chainage_to' => $request->road_length,
                'zone_cd' => $request->zone_cd,
                'circle_cd' => $request->circle_cd,
                'division_cd' => $request->division_cd,
                'sub_division_cd' => $request->sub_division_cd,
                'remaining_chainage_length' => 0,
                'chainage_step_id' => '1',
                'chainage_created_at_office_cd' => Auth::user()->office,
                'chainage_created_by' => Auth::user()->id,
                'chainage_updated_by' => Auth::user()->id,
            ];
    
            $status = AssetRoadChainageMapping::create($data);
            if ($status) {
                return redirect()->back()
                    ->with('success', 'Chainage created successfully!');
            } else {
                return redirect()->back()
                    ->with('failed', 'Failed to create chainage!');
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }
}
