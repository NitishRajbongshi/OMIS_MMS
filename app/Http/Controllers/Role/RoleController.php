<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoleDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\TempRoadModifyDetail;
use App\Models\Road\AssetModificationRequestRoadAssets;
use App\Models\UserMenuDetail;
use App\Helpers\MyHelper;


class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    
    public function GetAddRole()
    {
        try {
            $roledetails = RoleDetail::all();
            $userid = Auth::user()->id;
            $userName = User::select('name')->where('id', $userid)->value('name');
            $userRoleId = User::select('user_role_id')->where('id', $userid)->value('user_role_id');

            $roleData = DB::table('role_details')
                ->join('user_role_details', 'role_details.id', '=', 'user_role_details.role_id')
                ->select('role_details.*')
                ->where('user_role_details.user_id', $userid)
                ->orderBy('user_role_details.updated_at', 'desc')
                ->get();

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

            // $roadReqForUpdate = TempRoadModifyDetail::select('request_sent_by')->where('approve_status', 'P')->get()->count();
            $roadReqForUpdate = AssetModificationRequestRoadAssets::select('request_id')->where('modification_request_status', 'N')->get()->count();
            $menus = UserMenuDetail::select('menuid')->where('userid', $userid)->get();
            $menu = $menus->pluck('menuid')->toArray();

            return view(
                'roledetail.addrole',
                compact(
                    'roledetails',
                    'inserted',
                    'deleted',
                    'viewed',
                    'updated',
                    'userName',
                    'roadReqForUpdate',
                    'menu',
                    'userRoleId'
                )
            );
        } catch (Exception $e) {
        }
    }

    public function AddRole(Request $request)
    {
        try {
            if ($request->ajax()) {

                $validator = Validator::make($request->all(), [
                    'rolename' => 'required|string|max:255',
                ], [
                    'rolename.required' => 'This field is required'
                ]);
                if ($validator->fails()) {

                    return response()->json([
                        'message' => 'validationFails',
                        'error' => $validator->errors()
                    ]);
                } else {
                    $isNameExist = RoleDetail::whereRaw('LOWER(rolename) = ?', [strtolower($request->rolename)])->exists();

                    if (!$isNameExist) {
                        $RoleDetail = new RoleDetail();
                        $RoleDetail->rolename = $request->rolename;
                        $RoleDetail->roletype = $request->roletype;
                        $RoleDetail->created_at = Carbon::now();
                        $RoleDetail->updated_at = Carbon::now();
                        $RoleDetail->inserted = isset($request->inserted) ? $request->inserted : '0';
                        $RoleDetail->viewed = isset($request->viewed) ? $request->viewed : '0';
                        $RoleDetail->updated = isset($request->updated) ? $request->updated : '0';
                        $RoleDetail->deleted = isset($request->deleted) ? $request->deleted : '0';
                        $RoleDetail->freeze_data = isset($request->freeze) ? $request->freeze : '0';
                        $RoleDetail->req_modify = isset($request->reqModify) ? $request->reqModify : '0';
                        $RoleDetail->approve_req_modify = isset($request->approveModifyRequest) ? $request->approveModifyRequest : '0';
                        $RoleDetail->upload_file = isset($request->uploadFile) ? $request->uploadFile : '0';
                        $RoleDetail->role_created_by = Auth::user()->id;

                        $status = $RoleDetail->save();

                        // $U_id = Auth::user()->id;
                        // $ipAddress = $request->ip();
                        // $desc = 'Role Created by id: '.$U_id.' and the newly created role id is: '.$RoleDetail->id;
                        // MyHelper::addActivityLog(Auth::user()->id, 'Role-'.$RoleDetail->id, $desc, $ipAddress );
                        if ($status) {
                            return response()->json([
                                'message' => 'success',
                            ]);
                        } else {
                            return response()->json([
                                'message' => 'failed',
                            ]);
                        }
                    } else {
                        return response()->json([
                            'message' => 'duplicate'
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            return $e;
            return response()->json([
                'messege' => 'error',
                'request' => 'Something Went Wrong',
            ]);
        }
    }

    public function roleList()
    {
        $roledetails = RoleDetail::all();

        $userid = Auth::user()->id;
        $userName = User::select('name')->where('id', $userid)->value('name');
        $userRoleId = User::select('user_role_id')->where('id', $userid)->value('user_role_id');
        $roletype = RoleDetail::select('roletype')->where('id', $userRoleId)->value('roletype');

        $roleData = DB::table('role_details')
            ->join('user_role_details', 'role_details.id', '=', 'user_role_details.role_id')
            ->select('role_details.*')
            ->where('user_role_details.user_id', $userid)
            ->orderBy('user_role_details.updated_at', 'desc')
            ->get();

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

        // $roadReqForUpdate = TempRoadModifyDetail::select('request_sent_by')->where('approve_status', 'P')->get()->count();
        $roadReqForUpdate = AssetModificationRequestRoadAssets::select('request_id')->where('modification_request_status', 'N')->get()->count();

        return view('roledetail.roledetails', compact('roledetails', 'roletype', 'inserted', 'viewed', 'deleted', 'updated', 'userName', 'roadReqForUpdate'));
    }

    //check role name exist
    public function checkRoleName($rname)
    {
        $nameOfRole = RoleDetail::where('rolename', $rname)->exists();
        if ($nameOfRole == 0) {
            return response()->json(['success' => 'false']);
        } else {
            return response()->json(['success' => 'true']);
        }
    }

    public function updateRole(Request $request)
    {
        try {

            $roledata = RoleDetail::find($request->id);
            $roledata->inserted = isset($request->inserted) ? $request->inserted : 0;
            $roledata->viewed = isset($request->viewed) ? $request->viewed : 0;
            $roledata->deleted = isset($request->deleted) ? $request->deleted : 0;
            $roledata->updated = isset($request->updated) ? $request->updated : 0;
            $roledata->updated = isset($request->updated) ? $request->updated : 0;
            $roledata->freeze_data = isset($request->freeze) ? $request->freeze : 0;
            $roledata->req_modify = isset($request->req_modify) ? $request->req_modify : 0;
            $roledata->approve_req_modify = isset($request->approve_req_modify) ? $request->approve_req_modify : 0;
            $roledata->upload_file = isset($request->uploadFile) ? $request->uploadFile : 0;
            $roledata->created_at = Carbon::now();
            $roledata->updated_at = Carbon::now();
            $roledata->save();

            // $U_id = Auth::user()->id;
            // $ipAddress = $request->ip();
            // $desc = 'Role updated by id: ' . $U_id . '. The updated role id is: ' . $roledata->id;
            // MyHelper::upActivityLog(Auth::user()->id, 'Role-' . $roledata->id, $desc, $ipAddress);

            alert()->success('Data Updated successfully')->persistent('Close')->autoclose(3000);
            return redirect()->route('GetAddRole');
        } catch (Exception $e) {
            return $e;
        }
    }
}
