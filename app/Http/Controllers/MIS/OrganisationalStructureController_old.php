<?php

namespace App\Http\Controllers\MIS;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrganisationalStructureController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }


    public function getENCTreeData()
    {
        try {
            DB::enableQueryLog();
            $enc_ofs_dtls = DB::table('office_details AS ofd')
                ->select(
                    "ofd.id",
                    "ofd.office_name",
                    "ofd.office_type_cd",
                    "of_tp.office_type_desc",
                    "ofd.department_id",
                    "dept.department_name",
                    "ofd.zone_cd",
                    "ofd.circle_cd",
                    "ofd.division_cd",
                    "ofd.sub_division_cd"
                )
                ->leftjoin('asset_master_office_types as of_tp', 'of_tp.office_type_cd', '=', 'ofd.office_type_cd')
                ->leftJoin('asset_master_zones as zn', 'zn.zone_cd', '=', 'ofd.zone_cd')
                ->leftJoin('asset_master_circles as crl', 'crl.circle_cd', '=', 'ofd.circle_cd')
                ->leftJoin('asset_master_divisions as dv', 'dv.division_cd', '=', 'ofd.division_cd')
                ->leftJoin('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'ofd.sub_division_cd')
                ->join('department_details as dept', 'dept.id', '=', 'ofd.department_id')
                ->where('ofd.office_type_cd', '=', 'ECO')
                ->orderBy('ofd.department_id', 'desc')
                ->orderBy('of_tp.precedence_order', 'asc')
                ->orderBy('ofd.office_type_cd', 'desc')
                ->get();
            $enc_user_dtls = DB::table('office_details AS ofd')
                ->select(
                    "ofd.office_name",
                    "ofd.office_type_cd",
                    "ofd.department_id",
                    "dept.department_name",
                    "ofd.zone_cd",
                    "zn.zone_name",
                    "ofd.circle_cd",
                    "crl.circle_name",
                    "ofd.division_cd",
                    "dv.division_name",
                    "ofd.sub_division_cd",
                    "sdv.sub_div_name",
                    "of_tp.office_type_desc",
                    "u.name",
                    "u.email",
                    "u.phoneno",
                    "u.designation",
                    "u.since_current_position",
                    "d.desg_name"
                )
                ->leftjoin('asset_master_office_types as of_tp', 'of_tp.office_type_cd', '=', 'ofd.office_type_cd')
                ->leftJoin('users as u', 'u.office', '=', 'ofd.id')
                ->leftJoin('desg_details as d', 'd.id', '=', 'u.designation')
                ->leftJoin('asset_master_zones as zn', 'zn.zone_cd', '=', 'ofd.zone_cd')
                ->leftJoin('asset_master_circles as crl', 'crl.circle_cd', '=', 'ofd.circle_cd')
                ->leftJoin('asset_master_divisions as dv', 'dv.division_cd', '=', 'ofd.division_cd')
                ->leftJoin('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'ofd.sub_division_cd')
                ->leftJoin('department_details as dept', 'dept.id', '=', 'u.department')
                ->where('ofd.office_type_cd', '=', 'ECO')
                ->where('u.office_type_cd', '!=', 'SO')
                ->where('u.office_type_cd', '!=', 'ADM')
                ->where('u.office_type_cd', '!=', 'DA')
                ->orderBy('ofd.department_id', 'desc')
                ->orderBy('d.desg_precedence_id', 'asc')
                ->orderBy('zn.zone_name', 'asc')
                ->orderBy('crl.circle_name', 'asc')
                ->orderBy('dv.division_name', 'asc')
                ->orderBy('sdv.sub_div_name', 'asc')
                ->orderBy('ofd.office_name', 'desc')
                ->get();


            $count_zn = DB::table('asset_master_zones AS zn')
                ->select(
                    DB::raw('count(zn.zone_cd) as total')
                )->where("zn.dept_cd", "=", "14")
                ->get();

            $count_zn_hsng = DB::table('asset_master_zones AS zn')
            ->select(
                DB::raw('count(zn.zone_cd) as total')
            )->where("zn.dept_cd", "=", "6")
            ->get();
            $count_zn_mech = DB::table('asset_master_zones AS zn')
            ->select(
                DB::raw('count(zn.zone_cd) as total')
            )->where("zn.dept_cd", "=", "15")
            ->get();

            $count_zn_nh = DB::table('asset_master_zones AS zn')
                ->select(
                    DB::raw('count(zn.zone_cd) as total')
                )->where("zn.dept_cd", "=", "3")
                ->get();


            $count_crl = DB::table('asset_master_circles AS crl')
                ->select(
                    DB::raw('count(crl.circle_cd) as total')
                )
                ->where("crl.dept_cd", "=", "14")
                ->get();
            $count_crl_hsng = DB::table('asset_master_circles AS crl')
            ->select(
                DB::raw('count(crl.circle_cd) as total')
            )
            ->where("crl.dept_cd", "=", "6")
            ->get();

            $count_crl_mech = DB::table('asset_master_circles AS crl')
            ->select(
                DB::raw('count(crl.circle_cd) as total')
            )
            ->where("crl.dept_cd", "=", "15")
            ->get();

            $count_crl_nh = DB::table('asset_master_circles AS crl')
            ->select(
                DB::raw('count(crl.circle_cd) as total')
            )
            ->where("crl.dept_cd", "=", "3")
            ->get();

            $count_div = DB::table('asset_master_divisions AS dv')
                ->select(
                    DB::raw('count(dv.division_cd) as total')
                )
                ->where("dv.dept_cd", "=", "14")
                ->get();
            $count_div_hsng = DB::table('asset_master_divisions AS dv')
                ->select(
                    DB::raw('count(dv.division_cd) as total')
                )
                ->where("dv.dept_cd", "=", "6")
                ->get();
            $count_div_mech = DB::table('asset_master_divisions AS dv')
                ->select(
                    DB::raw('count(dv.division_cd) as total')
                )
                ->where("dv.dept_cd", "=", "15")
                ->get();
            $count_div_nh = DB::table('asset_master_divisions AS dv')
                ->select(
                    DB::raw('count(dv.division_cd) as total')
                )
                ->where("dv.dept_cd", "=", "3")
                ->get();
            
            
            $count_sdiv = DB::table('asset_master_sub_divisions AS sdv')
                ->select(
                    DB::raw('count(sdv.sub_div_cd) as total')
                )
                ->where("sdv.dept_cd", "=", "14")
                ->get();
            
            $count_sdiv_hsng = DB::table('asset_master_sub_divisions AS sdv')
                ->select(
                    DB::raw('count(sdv.sub_div_cd) as total')
                )
                ->where("sdv.dept_cd", "=", "6")
                ->get();
            
            $count_sdiv_mech = DB::table('asset_master_sub_divisions AS sdv')
                ->select(
                    DB::raw('count(sdv.sub_div_cd) as total')
                )
                ->where("sdv.dept_cd", "=", "15")
                ->get();
            $count_sdiv_nh = DB::table('asset_master_sub_divisions AS sdv')
                ->select(
                    DB::raw('count(sdv.sub_div_cd) as total')
                )
                ->where("sdv.dept_cd", "=", "3")
                ->get();



            $count_zn_for_hq = DB::table('asset_master_zones AS zn')
                ->select(
                    DB::raw('count(zn.zone_cd) as total'),
                    "zn.dept_cd"
                )->groupBy("zn.dept_cd")
                ->get();


            $count_crl_for_hq = DB::table('asset_master_circles AS crl')
                ->select(
                    DB::raw('count(crl.circle_cd) as total'),
                    "crl.dept_cd"
                )->groupBy("crl.dept_cd")
                ->get();

            $count_div_for_hq = DB::table('asset_master_divisions AS dv')
                ->select(
                    DB::raw('count(dv.division_cd) as total'),
                    "dv.dept_cd"
                )->groupBy("dv.dept_cd")
                ->get();

            $count_sdiv_for_hq = DB::table('asset_master_sub_divisions AS sdv')
                ->select(
                    DB::raw('count(sdv.sub_div_cd) as total'),
                    "sdv.dept_cd"
                )->groupBy("sdv.dept_cd")
                ->get();


            $query = DB::getQueryLog();
            Log::info($query);
        } catch (Exception $e) {
            Log::error("Error in loadOrganisationalStructure Data: " . $e->getMessage());
        }

        return view(
            'mis.organisationalStructure',
            compact(
                'enc_ofs_dtls',
                'enc_user_dtls',
                'count_zn',
                'count_zn_nh',
                'count_zn_hsng',
                'count_zn_mech',
                'count_crl',
                'count_crl_hsng',
                'count_crl_mech',
                'count_crl_nh',
                'count_div',
                'count_div_hsng',
                'count_div_mech',
                'count_div_nh',
                'count_sdiv',
                'count_sdiv_hsng',
                'count_sdiv_mech',
                'count_sdiv_nh',
                'count_zn_for_hq',
                'count_crl_for_hq',
                'count_div_for_hq',
                'count_sdiv_for_hq'
            )
        );
    }


    public function getHQTreeData(Request $request)
    {
        $dept_id  = $request->dept_id;
        DB::enableQueryLog();
        $hq_ofs_dtls = DB::table('office_details AS ofd')
            ->select(
                "ofd.id",
                "ofd.office_name",
                "ofd.office_type_cd",
                "of_tp.office_type_desc",
                "ofd.department_id",
                "dept.department_name",
                "ofd.zone_cd",
                "ofd.circle_cd",
                "ofd.division_cd",
                "ofd.sub_division_cd"
            )
            ->leftjoin('asset_master_office_types as of_tp', 'of_tp.office_type_cd', '=', 'ofd.office_type_cd')
            ->leftJoin('asset_master_zones as zn', 'zn.zone_cd', '=', 'ofd.zone_cd')
            ->leftJoin('asset_master_circles as crl', 'crl.circle_cd', '=', 'ofd.circle_cd')
            ->leftJoin('asset_master_divisions as dv', 'dv.division_cd', '=', 'ofd.division_cd')
            ->leftJoin('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'ofd.sub_division_cd')
            ->join('department_details as dept', 'dept.id', '=', 'ofd.department_id')
            ->where('ofd.office_type_cd', '=', 'HQ')
            ->where('ofd.department_id', $dept_id)
            ->orderBy('ofd.department_id', 'desc')
            ->orderBy('of_tp.precedence_order', 'asc')
            ->orderBy('ofd.office_type_cd', 'desc')
            ->get();


        $hq_user_data = DB::table('office_details AS ofd')
            ->select(
                "ofd.office_name",
                "ofd.office_type_cd",
                "ofd.department_id",
                "dept.department_name",
                "ofd.zone_cd",
                "zn.zone_name",
                "ofd.circle_cd",
                "crl.circle_name",
                "ofd.division_cd",
                "dv.division_name",
                "ofd.sub_division_cd",
                "sdv.sub_div_name",
                "of_tp.office_type_desc",
                "u.name",
                "u.email",
                "u.phoneno",
                "u.designation",
                "u.since_current_position",
                "d.desg_name"
            )
            ->leftjoin('asset_master_office_types as of_tp', 'of_tp.office_type_cd', '=', 'ofd.office_type_cd')
            ->leftJoin('users as u', 'u.office', '=', 'ofd.id')
            ->leftJoin('desg_details as d', 'd.id', '=', 'u.designation')
            ->leftJoin('asset_master_zones as zn', 'zn.zone_cd', '=', 'ofd.zone_cd')
            ->leftJoin('asset_master_circles as crl', 'crl.circle_cd', '=', 'ofd.circle_cd')
            ->leftJoin('asset_master_divisions as dv', 'dv.division_cd', '=', 'ofd.division_cd')
            ->leftJoin('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'ofd.sub_division_cd')
            ->leftJoin('department_details as dept', 'dept.id', '=', 'u.department')
            ->where('ofd.office_type_cd', '=', 'HQ')
            ->where('u.office_type_cd', '!=', 'SO')
            ->where('u.office_type_cd', '!=', 'ADM')
            ->where('u.office_type_cd', '!=', 'DA')
            ->orderBy('ofd.department_id', 'desc')
            ->orderBy('d.desg_precedence_id', 'asc')
            ->orderBy('zn.zone_name', 'asc')
            ->orderBy('crl.circle_name', 'asc')
            ->orderBy('dv.division_name', 'asc')
            ->orderBy('sdv.sub_div_name', 'asc')
            ->orderBy('ofd.office_name', 'desc')
            ->get();
        $addl_cf_eng_ofs_data = DB::table('office_details AS ofd')
            ->select(
                "ofd.id",
                "ofd.office_name",
                "ofd.office_type_cd",
                "of_tp.office_type_desc",
                "ofd.department_id",
                "dept.department_name",
                "ofd.zone_cd",
                "ofd.circle_cd",
                "ofd.division_cd",
                "ofd.sub_division_cd"
            )
            ->leftjoin('asset_master_office_types as of_tp', 'of_tp.office_type_cd', '=', 'ofd.office_type_cd')
            ->leftJoin('asset_master_zones as zn', 'zn.zone_cd', '=', 'ofd.zone_cd')
            ->leftJoin('asset_master_circles as crl', 'crl.circle_cd', '=', 'ofd.circle_cd')
            ->leftJoin('asset_master_divisions as dv', 'dv.division_cd', '=', 'ofd.division_cd')
            ->leftJoin('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'ofd.sub_division_cd')
            ->join('department_details as dept', 'dept.id', '=', 'ofd.department_id')
            ->where('ofd.office_type_cd', '=', 'ZO')
            ->orderBy('ofd.department_id', 'desc')
            ->orderBy('ofd.office_name', 'asc')
            ->orderBy('of_tp.precedence_order', 'asc')
            ->orderBy('ofd.office_type_cd', 'desc')
            ->get();




        $count_crl = DB::table('asset_master_circles AS crl')
            ->select(
                DB::raw('count(crl.circle_cd) as total'),
                "crl.zone_cd",
                "crl.dept_cd"
            )
            ->groupBy("crl.dept_cd")
            ->groupBy("crl.zone_cd")
            ->get();

        $count_div = DB::table('asset_master_divisions AS dv')
            ->select(
                DB::raw('count(dv.division_cd) as total'),
                "dv.zone_cd",
                "dv.dept_cd"
            )->groupBy("dv.dept_cd")
            ->groupBy("dv.zone_cd")
            ->get();

        $count_sdiv = DB::table('asset_master_sub_divisions AS sdv')
            ->select(
                DB::raw('count(sdv.sub_div_cd) as total'),
                "sdv.zone_cd",
                "sdv.dept_cd"
            )->groupBy("sdv.dept_cd")
            ->groupBy("sdv.zone_cd")
            ->get();



        $query = DB::getQueryLog();
        Log::info($query);
        if ($hq_ofs_dtls) {
            return response()->json([
                'status' => '1',
                'hq_office_data' => $hq_ofs_dtls,
                'hq_user_data' => $hq_user_data,
                'addl_cf_eng_ofs_data' => $addl_cf_eng_ofs_data,
                'count_crl' => $count_crl,
                'count_div' => $count_div,
                'count_sdiv' => $count_sdiv
            ]);
        } else {
            return response()->json([
                'status' => '0'
            ]);
        }
    }


    public function getZOtreeData(Request $request)
    {
        DB::enableQueryLog();
        $office_cd  = $request->office_cd;
        $dept_id  = $request->dept_id;
        $zone_cd  = $request->zone_cd;
        $circle_cd  = $request->circle_cd;
        $division_cd  = $request->division_cd;
        $sub_division_cd  = $request->sub_division_cd;

        $zo_user_data = DB::table('office_details AS ofd')
            ->select(
                "ofd.office_name",
                "ofd.office_type_cd",
                "ofd.department_id",
                "dept.department_name",
                "ofd.zone_cd",
                "zn.zone_name",
                "ofd.circle_cd",
                "crl.circle_name",
                "ofd.division_cd",
                "dv.division_name",
                "ofd.sub_division_cd",
                "sdv.sub_div_name",
                "of_tp.office_type_desc",
                "u.name",
                "u.email",
                "u.phoneno",
                "u.designation",
                "u.since_current_position",
                "d.desg_name"
            )
            ->leftjoin('asset_master_office_types as of_tp', 'of_tp.office_type_cd', '=', 'ofd.office_type_cd')
            ->leftJoin('users as u', 'u.office', '=', 'ofd.id')
            ->leftJoin('desg_details as d', 'd.id', '=', 'u.designation')
            ->leftJoin('asset_master_zones as zn', 'zn.zone_cd', '=', 'ofd.zone_cd')
            ->leftJoin('asset_master_circles as crl', 'crl.circle_cd', '=', 'ofd.circle_cd')
            ->leftJoin('asset_master_divisions as dv', 'dv.division_cd', '=', 'ofd.division_cd')
            ->leftJoin('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'ofd.sub_division_cd')
            ->leftJoin('department_details as dept', 'dept.id', '=', 'u.department')
            ->where('ofd.office_type_cd', '=', 'ZO')
            ->where('ofd.id', '=', $office_cd)
            ->where('u.department', '=', $dept_id)
            ->where('u.office', '=', $office_cd)
            ->where('u.office_type_cd', '!=', 'SO')
            ->where('u.office_type_cd', '!=', 'ADM')
            ->where('u.office_type_cd', '!=', 'DA')
            ->orderBy('ofd.department_id', 'desc')
            ->orderBy('d.desg_precedence_id', 'asc')
            ->orderBy('zn.zone_name', 'asc')
            ->orderBy('crl.circle_name', 'asc')
            ->orderBy('dv.division_name', 'asc')
            ->orderBy('sdv.sub_div_name', 'asc')
            ->orderBy('ofd.office_name', 'desc')
            ->get();


        $supd_eng_ofs_data = DB::table('office_details AS ofd')
            ->select(
                "ofd.id",
                "ofd.office_name",
                "ofd.office_type_cd",
                "of_tp.office_type_desc",
                "ofd.department_id",
                "dept.department_name",
                "ofd.zone_cd",
                "ofd.circle_cd",
                "ofd.division_cd",
                "ofd.sub_division_cd"
            )
            ->leftjoin('asset_master_office_types as of_tp', 'of_tp.office_type_cd', '=', 'ofd.office_type_cd')
            ->leftJoin('asset_master_zones as zn', 'zn.zone_cd', '=', 'ofd.zone_cd')
            ->leftJoin('asset_master_circles as crl', 'crl.circle_cd', '=', 'ofd.circle_cd')
            ->leftJoin('asset_master_divisions as dv', 'dv.division_cd', '=', 'ofd.division_cd')
            ->leftJoin('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'ofd.sub_division_cd')
            ->join('department_details as dept', 'dept.id', '=', 'ofd.department_id')
            ->where('ofd.office_type_cd', '=', 'CO')
            ->where('ofd.department_id', '=',  $dept_id)
            ->where('ofd.zone_cd', '=',  $zone_cd)
            // ->where('ofd.circle_cd', '=',  $circle_cd)
            ->orderBy('ofd.department_id', 'desc')
            ->orderBy('ofd.office_name', 'asc')
            ->orderBy('of_tp.precedence_order', 'asc')
            ->orderBy('ofd.office_type_cd', 'desc')
            ->get();
        $div_dtls = DB::table('asset_master_divisions AS dv')
            ->select(
                DB::raw('count(dv.division_cd) as total'),
                "dv.zone_cd",
                "dv.circle_cd",
                "dv.dept_cd"
            )->where("dv.dept_cd", '=',  $dept_id)
            ->groupBy("dv.dept_cd")
            ->groupBy("dv.zone_cd")
            ->groupBy("dv.circle_cd")
            ->get();

        $sub_div_dtls = DB::table('asset_master_sub_divisions AS sdv')
            ->select(
                DB::raw('count(sdv.sub_div_cd) as total'),
                "sdv.zone_cd",
                "sdv.circle_cd",
                "sdv.dept_cd"
            )->where("sdv.dept_cd", '=',  $dept_id)
            ->groupBy("sdv.dept_cd")
            ->groupBy("sdv.zone_cd")
            ->groupBy("sdv.circle_cd")
            ->get();
        $query = DB::getQueryLog();
        Log::info($query);
        // if ($zo_user_data) {
        return response()->json([
            'status' => '1',
            'zo_user_data' => $zo_user_data,
            'supd_eng_ofs_data' => $supd_eng_ofs_data,
            'div_dtls' => $div_dtls,
            'sub_div_dtls' => $sub_div_dtls
        ]);
        // }
        // else{
        //     return response()->json([
        //         'status' => '0']);
        // }
    }

    public function getCOtreeData(Request $request)
    {
        DB::enableQueryLog();
        $office_cd  = $request->office_cd;
        $dept_id  = $request->dept_id;
        $zone_cd  = $request->zone_cd;
        $circle_cd  = $request->circle_cd;

        $co_user_data = DB::table('office_details AS ofd')
            ->select(
                "ofd.office_name",
                "ofd.office_type_cd",
                "ofd.department_id",
                "dept.department_name",
                "ofd.zone_cd",
                "zn.zone_name",
                "ofd.circle_cd",
                "crl.circle_name",
                "ofd.division_cd",
                "dv.division_name",
                "ofd.sub_division_cd",
                "sdv.sub_div_name",
                "of_tp.office_type_desc",
                "u.name",
                "u.email",
                "u.phoneno",
                "u.designation",
                "u.since_current_position",
                "d.desg_name"
            )
            ->leftjoin('asset_master_office_types as of_tp', 'of_tp.office_type_cd', '=', 'ofd.office_type_cd')
            ->leftJoin('users as u', 'u.office', '=', 'ofd.id')
            ->leftJoin('desg_details as d', 'd.id', '=', 'u.designation')
            ->leftJoin('asset_master_zones as zn', 'zn.zone_cd', '=', 'ofd.zone_cd')
            ->leftJoin('asset_master_circles as crl', 'crl.circle_cd', '=', 'ofd.circle_cd')
            ->leftJoin('asset_master_divisions as dv', 'dv.division_cd', '=', 'ofd.division_cd')
            ->leftJoin('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'ofd.sub_division_cd')
            ->leftJoin('department_details as dept', 'dept.id', '=', 'u.department')
            ->where('ofd.office_type_cd', '=', 'CO')
            ->where('ofd.id', '=', $office_cd)
            ->where('u.department', '=', $dept_id)
            ->where('u.office', '=', $office_cd)
            ->where('u.office_type_cd', '!=', 'SO')
            ->where('u.office_type_cd', '!=', 'ADM')
            ->where('u.office_type_cd', '!=', 'DA')
            ->orderBy('ofd.department_id', 'desc')
            ->orderBy('d.desg_precedence_id', 'asc')
            ->orderBy('zn.zone_name', 'asc')
            ->orderBy('crl.circle_name', 'asc')
            ->orderBy('dv.division_name', 'asc')
            ->orderBy('sdv.sub_div_name', 'asc')
            ->orderBy('ofd.office_name', 'desc')
            ->get();


        $do_ofs_data = DB::table('office_details AS ofd')
            ->select(
                "ofd.id",
                "ofd.office_name",
                "ofd.office_type_cd",
                "of_tp.office_type_desc",
                "ofd.department_id",
                "dept.department_name",
                "ofd.zone_cd",
                "ofd.circle_cd",
                "ofd.division_cd",
                "ofd.sub_division_cd"
            )
            ->leftjoin('asset_master_office_types as of_tp', 'of_tp.office_type_cd', '=', 'ofd.office_type_cd')
            ->leftJoin('asset_master_zones as zn', 'zn.zone_cd', '=', 'ofd.zone_cd')
            ->leftJoin('asset_master_circles as crl', 'crl.circle_cd', '=', 'ofd.circle_cd')
            ->leftJoin('asset_master_divisions as dv', 'dv.division_cd', '=', 'ofd.division_cd')
            ->leftJoin('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'ofd.sub_division_cd')
            ->join('department_details as dept', 'dept.id', '=', 'ofd.department_id')
            ->where('ofd.office_type_cd', '=', 'DO')
            ->where('ofd.department_id', '=',  $dept_id)
            ->where('ofd.zone_cd', '=',  $zone_cd)
            ->where('ofd.circle_cd', '=',  $circle_cd)
            ->orderBy('ofd.department_id', 'desc')
            ->orderBy('ofd.office_name', 'asc')
            ->orderBy('of_tp.precedence_order', 'asc')
            ->orderBy('ofd.office_type_cd', 'desc')
            ->get();
        $sub_div_dtls = DB::table('asset_master_sub_divisions AS sdv')
            ->select(
                DB::raw('count(sdv.sub_div_cd) as total'),
                "sdv.zone_cd",
                "sdv.circle_cd",
                "sdv.div_cd",
                "sdv.dept_cd"
            )->where("sdv.dept_cd", '=',  $dept_id)
            ->groupBy("sdv.dept_cd")
            ->groupBy("sdv.zone_cd")
            ->groupBy("sdv.circle_cd")
            ->groupBy("sdv.div_cd")
            ->get();
        $query = DB::getQueryLog();
        Log::info($query);

        return response()->json([
            'status' => '1',
            'co_user_data' => $co_user_data,
            'do_ofs_data' => $do_ofs_data,
            'sub_div_dtls' => $sub_div_dtls
        ]);
    }



    public function getDOtreeData(Request $request)
    {
        DB::enableQueryLog();
        $office_cd  = $request->office_cd;
        $dept_id  = $request->dept_id;
        $zone_cd  = $request->zone_cd;
        $circle_cd  = $request->circle_cd;
        $division_cd  = $request->division_cd;

        $do_user_data = DB::table('office_details AS ofd')
            ->select(
                "ofd.office_name",
                "ofd.office_type_cd",
                "ofd.department_id",
                "dept.department_name",
                "ofd.zone_cd",
                "zn.zone_name",
                "ofd.circle_cd",
                "crl.circle_name",
                "ofd.division_cd",
                "dv.division_name",
                "ofd.sub_division_cd",
                "sdv.sub_div_name",
                "of_tp.office_type_desc",
                "u.name",
                "u.email",
                "u.phoneno",
                "u.designation",
                "u.since_current_position",
                "d.desg_name"
            )
            ->leftjoin('asset_master_office_types as of_tp', 'of_tp.office_type_cd', '=', 'ofd.office_type_cd')
            ->leftJoin('users as u', 'u.office', '=', 'ofd.id')
            ->leftJoin('desg_details as d', 'd.id', '=', 'u.designation')
            ->leftJoin('asset_master_zones as zn', 'zn.zone_cd', '=', 'ofd.zone_cd')
            ->leftJoin('asset_master_circles as crl', 'crl.circle_cd', '=', 'ofd.circle_cd')
            ->leftJoin('asset_master_divisions as dv', 'dv.division_cd', '=', 'ofd.division_cd')
            ->leftJoin('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'ofd.sub_division_cd')
            ->leftJoin('department_details as dept', 'dept.id', '=', 'u.department')
            ->where('ofd.office_type_cd', '=', 'DO')
            ->where('ofd.id', '=', $office_cd)
            ->where('u.department', '=', $dept_id)
            ->where('u.office', '=', $office_cd)
            ->where('u.office_type_cd', '!=', 'SO')
            ->where('u.office_type_cd', '!=', 'ADM')
            ->where('u.office_type_cd', '!=', 'DA')
            ->orderBy('ofd.department_id', 'desc')
            ->orderBy('d.desg_precedence_id', 'asc')
            ->orderBy('zn.zone_name', 'asc')
            ->orderBy('crl.circle_name', 'asc')
            ->orderBy('dv.division_name', 'asc')
            ->orderBy('sdv.sub_div_name', 'asc')
            ->orderBy('ofd.office_name', 'desc')
            ->get();


        $sdo_ofs_data = DB::table('office_details AS ofd')
            ->select(
                "ofd.id",
                "ofd.office_name",
                "ofd.office_type_cd",
                "of_tp.office_type_desc",
                "ofd.department_id",
                "dept.department_name",
                "ofd.zone_cd",
                "ofd.circle_cd",
                "ofd.division_cd",
                "ofd.sub_division_cd"
            )
            ->leftjoin('asset_master_office_types as of_tp', 'of_tp.office_type_cd', '=', 'ofd.office_type_cd')
            ->leftJoin('asset_master_zones as zn', 'zn.zone_cd', '=', 'ofd.zone_cd')
            ->leftJoin('asset_master_circles as crl', 'crl.circle_cd', '=', 'ofd.circle_cd')
            ->leftJoin('asset_master_divisions as dv', 'dv.division_cd', '=', 'ofd.division_cd')
            ->leftJoin('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'ofd.sub_division_cd')
            ->join('department_details as dept', 'dept.id', '=', 'ofd.department_id')
            ->where('ofd.office_type_cd', '=', 'SDO')
            ->where('ofd.department_id', '=',  $dept_id)
            ->where('ofd.zone_cd', '=',  $zone_cd)
            ->where('ofd.circle_cd', '=',  $circle_cd)
            ->where('ofd.division_cd', '=',  $division_cd)
            ->orderBy('ofd.department_id', 'desc')
            ->orderBy('ofd.office_name', 'asc')
            ->orderBy('of_tp.precedence_order', 'asc')
            ->orderBy('ofd.office_type_cd', 'desc')
            ->get();

        $query = DB::getQueryLog();
        Log::info($query);

        return response()->json([
            'status' => '1',
            'do_user_data' => $do_user_data,
            'sdo_ofs_data' => $sdo_ofs_data,
            'total_zone_rnb' => 0,
            'total_circle_rnb' => 0,
            'total_div_rnb' => 0,
            'total_sub_div_rnb' => 0,
            'total_zone_hsng' => 0,
            'total_circle_hsng' => 0,
            'total_div_hsng' => 0,
            'total_sub_div_hsng' => 0,
            'total_zone_mech' => 0,
            'total_circle_mech' => 0,
            'total_div_mech' => 0,
            'total_sub_div_mech' => 0,
            'total_zone_nh' => 0,
            'total_circle_nh' => 0,
            'total_div_nh' => 0,
            'total_sub_div_nh' => 0,
        ]);
    }


    public function getSDOtreeData(Request $request)
    {
        DB::enableQueryLog();
        $office_cd  = $request->office_cd;
        $dept_id  = $request->dept_id;
        $zone_cd  = $request->zone_cd;
        $circle_cd  = $request->circle_cd;
        $division_cd  = $request->division_cd;
        $sub_division_cd  = $request->sub_division_cd;

        $sdo_user_data = DB::table('office_details AS ofd')
            ->select(
                "ofd.office_name",
                "ofd.office_type_cd",
                "ofd.department_id",
                "dept.department_name",
                "ofd.zone_cd",
                "zn.zone_name",
                "ofd.circle_cd",
                "crl.circle_name",
                "ofd.division_cd",
                "dv.division_name",
                "ofd.sub_division_cd",
                "sdv.sub_div_name",
                "of_tp.office_type_desc",
                "u.name",
                "u.email",
                "u.phoneno",
                "u.designation",
                "u.since_current_position",
                "d.desg_name"
            )
            ->leftjoin('asset_master_office_types as of_tp', 'of_tp.office_type_cd', '=', 'ofd.office_type_cd')
            ->leftJoin('users as u', 'u.office', '=', 'ofd.id')
            ->leftJoin('desg_details as d', 'd.id', '=', 'u.designation')
            ->leftJoin('asset_master_zones as zn', 'zn.zone_cd', '=', 'ofd.zone_cd')
            ->leftJoin('asset_master_circles as crl', 'crl.circle_cd', '=', 'ofd.circle_cd')
            ->leftJoin('asset_master_divisions as dv', 'dv.division_cd', '=', 'ofd.division_cd')
            ->leftJoin('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'ofd.sub_division_cd')
            ->leftJoin('department_details as dept', 'dept.id', '=', 'u.department')
            ->where('ofd.office_type_cd', '=', 'SDO')
            ->where('ofd.id', '=', $office_cd)
            ->where('u.department', '=', $dept_id)
            ->where('u.office', '=', $office_cd)
            ->where('u.office_type_cd', '!=', 'SO')
            ->where('u.office_type_cd', '!=', 'ADM')
            ->where('u.office_type_cd', '!=', 'DA')
            ->orderBy('ofd.department_id', 'desc')
            ->orderBy('d.desg_precedence_id', 'asc')
            ->orderBy('zn.zone_name', 'asc')
            ->orderBy('crl.circle_name', 'asc')
            ->orderBy('dv.division_name', 'asc')
            ->orderBy('sdv.sub_div_name', 'asc')
            ->orderBy('ofd.office_name', 'desc')
            ->get();

        $query = DB::getQueryLog();
        Log::info($query);

        return response()->json([
            'status' => '1',
            'sdo_user_data' => $sdo_user_data,
            'total_zone_rnb' => 0,
            'total_circle_rnb' => 0,
            'total_div_rnb' => 0,
            'total_sub_div_rnb' => 0,
            'total_zone_hsng' => 0,
            'total_circle_hsng' => 0,
            'total_div_hsng' => 0,
            'total_sub_div_hsng' => 0,
            'total_zone_mech' => 0,
            'total_circle_mech' => 0,
            'total_div_mech' => 0,
            'total_sub_div_mech' => 0,
            'total_zone_nh' => 0,
            'total_circle_nh' => 0,
            'total_div_nh' => 0,
            'total_sub_div_nh' => 0,
        ]);
    }
}