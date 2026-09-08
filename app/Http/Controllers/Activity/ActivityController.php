<?php

namespace App\Http\Controllers\Activity;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\LoginLogDetail;
use App\Models\UserMenuDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Road\AssetModificationRequestRoadAssets;

class ActivityController extends Controller
{
    public function getLoginLog() {
        $logdata = LoginLogDetail::orderBy('id','desc')->get();
        $logdata = $logdata->map(function ($logDetail) {
            $name = User::where('id', $logDetail->userId)->value('name');
            $email = User::where('id', $logDetail->userId)->value('email');
            $result = [
                'username' => $name,
                'useremail' => $email,
            ];
            $logDetail->user_info = $result;
            return $logDetail;
        });

        $userid = Auth::user()->id;
        $userName = User::select('name')->where('id', $userid)->value('name');
        $userRoleId = User::select('user_role_id')->where('id', $userid)->value('user_role_id');
        $userdetails = User::orderBy('id','asc')->get();

        $roleData = DB::table('role_details')->join('user_role_details', 'role_details.id', '=', 'user_role_details.role_id')
        ->select('role_details.*')->where('user_role_details.user_id', $userid)->get();

        $inserted = 0; $viewed = 0; $deleted = 0; $updated = 0;
        foreach($roleData as $rrr) {
                $ins = $rrr->inserted;
                $vie = $rrr->viewed;
                $del = $rrr->deleted;
                $upd = $rrr->updated;

                if($ins == 1) {
                    $inserted = 1;
                }
                if($vie == 1) {
                    $viewed = 1;
                }
                if($del == 1) {
                    $deleted = 1;
                }
                if($upd == 1) {
                    $updated = 1;
                }
        }

        $roadReqForUpdate =  AssetModificationRequestRoadAssets::select('request_id')->where('modification_request_status', 'N')->get()->count();
        $menus = UserMenuDetail::select('menuid')->where('userid',$userid)->get();
        $menu = $menus->pluck('menuid')->toArray();

        return view('log.viewLogin', compact('logdata','inserted', 'viewed', 'deleted', 'updated',
                                                            'userName', 'roadReqForUpdate', 'menu', 'userRoleId', 'userdetails'));
    }

    public function getUserOnchange(Request $request)
    {
        $val = $request->input('selectedValue');

        if($val == 'all') {
                $data =  LoginLogDetail::orderBy('id', 'desc')->get();
        } else {

                $data =  LoginLogDetail::where('userId',$val)->orderBy('id', 'desc')->get();
        }
            $data = $data->map(function ($logDetail) {
                $name = User::where('id', $logDetail->userId)->value('name');
                $email = User::where('id', $logDetail->userId)->value('email');
                $result = [
                    'username' => $name,
                    'useremail' => $email,
                ];
                $logDetail->user_info = $result;
                return $logDetail;
            });

        //print_r(json_encode($data));die();
        return response()->json($data);
    }
}
