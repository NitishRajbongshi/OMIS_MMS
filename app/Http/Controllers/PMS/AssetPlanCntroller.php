<?php

namespace App\Http\Controllers\PMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssetPlanCntroller extends Controller
{

    public function __construct()
    {

        $this->middleware("auth");

    }
    public function index()
    {
        $user = Auth::user();

        $zone_cd = null;
        $circle_cd = null;
        $division_cd = null;
        $sub_division_cd = null;
        $dept_cd = session('user_dept_cd');

        $userMapping = DB::table('asset_user_mappings')
            ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd')
            ->where('user_id', '=', $user->id)
            ->get()->first();

        $zone_cd = $userMapping->zone_cd;
        $circle_cd = $userMapping->circle_cd;
        $division_cd = $userMapping->division_cd;
        $sub_division_cd = $userMapping->sub_division_cd;

        $rows = DB::table('prt_project_asset_plan as ap')
            ->join('prt_project_details as pd', 'pd.project_cd', '=', 'ap.project_cd')
            ->join('asset_master_divisions as dv', 'dv.division_cd', '=', 'pd.division_cd')
            ->join('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'pd.sub_division_cd')
            ->join('asset_master_road_sub_assets as astp', 'astp.sub_asset_cd', '=', 'ap.asset_type_cd')
            ->where('ap.project_type_cd', 'NEW')
            ->where('ap.status', 0)
            ->where('ap.no_of_new_asset', '>', 0)
            ->where('pd.owner_dept_cd', $dept_cd)
            ->orderBy('ap.id')
            ->orderBy('ap.project_cd')
            ->select(
                'ap.*',
                'pd.project_name',
                'pd.division_cd',
                'pd.sub_division_cd',
                'pd.owner_dept_cd',
                'dv.division_name',
                'sdv.sub_div_name',
                'pd.project_start_date',
                'pd.project_end_date',
                'astp.sub_assets_descr'
            )
            ->get();

        $rowsUpgrade = DB::table('prt_project_asset_plan as ap')
            ->join('prt_project_details as pd', 'pd.project_cd', '=', 'ap.project_cd')
            ->join('asset_master_divisions as dv', 'dv.division_cd', '=', 'pd.division_cd')
            ->join('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'pd.sub_division_cd')
            ->join('asset_master_road_sub_assets as astp', 'astp.sub_asset_cd', '=', 'ap.asset_type_cd')
            ->where('ap.project_type_cd', 'UPG')
            ->where('ap.status', 0)
            ->where('pd.owner_dept_cd', $dept_cd)
            ->orderBy('ap.id')
            ->orderBy('ap.project_cd')
            ->select(
                'ap.*',
                'pd.project_name',
                'pd.division_cd',
                'pd.sub_division_cd',
                'pd.owner_dept_cd',
                'dv.division_name',
                'sdv.sub_div_name',
                'pd.project_start_date',
                'pd.project_end_date',
                'astp.sub_assets_descr'
            )
            ->get();


        $rowsMaintenance = DB::table('prt_project_asset_plan as ap')
            ->join('prt_project_details as pd', 'pd.project_cd', '=', 'ap.project_cd')
            ->join('asset_master_divisions as dv', 'dv.division_cd', '=', 'pd.division_cd')
            ->join('asset_master_sub_divisions as sdv', 'sdv.sub_div_cd', '=', 'pd.sub_division_cd')
            ->join('asset_master_road_sub_assets as astp', 'astp.sub_asset_cd', '=', 'ap.asset_type_cd')
            ->where('ap.project_type_cd', 'MTN')
            ->where('ap.status', 0)
            ->where('pd.owner_dept_cd', $dept_cd)
            ->orderBy('ap.project_cd')
            ->orderBy('ap.asset_type_cd')
            ->orderBy('ap.id')
            ->select(
                'ap.*',
                'pd.project_name',
                'pd.division_cd',
                'pd.sub_division_cd',
                'pd.owner_dept_cd',
                'dv.division_name',
                'sdv.sub_div_name',
                'pd.project_start_date',
                'pd.project_end_date',
                'astp.sub_assets_descr'
            )
            ->get();


        $projects = [];
        $projectsUpgrade = [];
        $projectsMaintenance = [];
        $temp_parent_cd = null;
        $is_asset_exist_in_main_table = true;
        foreach ($rows as $row) {

            $proj = $row->project_cd;
            $type = $row->sub_assets_descr;
            $count = $row->no_of_new_asset ?? 1;
            $typeKey = $row->asset_type_cd;
            $typeName = $row->sub_assets_descr;
            switch ($typeKey) {
                case '0':
                case '1':
                case '12':
                    $is_asset_exist_in_main_table = DB::table('asset_road_details')
                        ->wherein('rd_system_id', [$row->parent_asset_cd])
                        ->exists();
                    break;
            }
            // Initialize project
            if (!isset($projects[$proj])) {
                $projects[$proj] = [
                    'info' => [
                        'project_cd' => $row->project_cd,
                        'dept_cd' => $row->owner_dept_cd,
                        'project_name' => $row->project_name,
                        'division' => $row->division_name,
                        'sub_division' => $row->sub_div_name,
                        'division_cd' => $row->division_cd,
                        'sub_division_cd' => $row->sub_division_cd,
                        'start_date' => $row->project_start_date,
                        'end_date' => $row->project_end_date,
                    ],
                    'summary' => [],
                    'details' => []
                ];
            }

            // Summary
            $typeKey = $row->asset_type_cd;
            $typeName = $row->sub_assets_descr;

            if (!isset($projects[$proj]['summary'][$typeKey])) {
                $projects[$proj]['summary'][$typeKey] = [
                    'asset_plan_id' => $row->id,
                    'name' => $typeName,
                    'count' => 0,
                    'asset_name' => $row->temp_asset_name ?? null,
                    'asset_cd' => $row->temp_asset_cd ?? null,
                    'parent_asset_cd' => $row->parent_asset_cd ?? null,
                    'new_asset_length' => $row->new_asset_length ?? null,
                    'is_asset_exist_in_main_table' => $is_asset_exist_in_main_table
                ];

            }

            $projects[$proj]['summary'][$typeKey]['count'] += $count;

            $projects[$proj]['details'][$typeKey][] = $row;
        }

        foreach ($rowsUpgrade as $row) {
            $proj = $row->project_cd;
            $count = $row->no_of_new_asset ?? 0;
            $typeKey = $row->asset_type_cd;
            $typeName = $row->sub_assets_descr;

            switch ($typeKey) {
                case '0':
                case '1':
                case '12':
                    $is_asset_exist_in_main_table = DB::table('asset_road_details')
                        ->wherein('rd_system_id', [$row->parent_asset_cd])
                        ->exists();
                    break;
            }
            if (!isset($projectsUpgrade[$proj])) {
                $projectsUpgrade[$proj] = [
                    'info' => [
                        'project_cd' => $row->project_cd,
                        'dept_cd' => $row->owner_dept_cd,
                        'project_name' => $row->project_name,
                        'division' => $row->division_name,
                        'sub_division' => $row->sub_div_name,
                        'division_cd' => $row->division_cd,
                        'sub_division_cd' => $row->sub_division_cd,
                        'start_date' => $row->project_start_date,
                        'end_date' => $row->project_end_date,
                    ],
                    'new_temp' => [],
                    'redefine' => [],
                    'new_existing' => []
                ];
            }


            if (!isset($projectsUpgrade[$proj]['new_temp'][$typeKey])) {
                $projectsUpgrade[$proj]['new_temp'][$typeKey] = [
                    'asset_plan_id' => $row->id,
                    'name' => $typeName,
                    'count' => 0,
                    'asset_name' => $row->temp_asset_name,
                    'temp_asset_cd' => $row->temp_asset_cd,
                    'parent_asset_cd' => $row->parent_asset_cd ?? null,
                    'new_asset_length' => $row->new_asset_length ?? null,
                    'is_asset_exist_in_main_table' => $is_asset_exist_in_main_table
                ];
            }
            if ($row->group_type_cd == "UPG_NEW") {
                $projectsUpgrade[$proj]['new_temp'][$typeKey]['count'] = $count;
            }


            if (!empty($row->upgraded_asset_cd)) {

                $projectsUpgrade[$proj]['redefine'][] = [
                    'asset_plan_id' => $row->id,
                    'type_key' => $typeKey,
                    'name' => $typeName,
                    'asset_name' => $row->temp_asset_name,
                    'asset_cd' => $row->upgraded_asset_cd,
                    'parent' => $row->parent_asset_cd
                ];
            }


            if (!empty($row->temp_asset_cd) && !empty($row->parent_asset_cd) && $count > 0 && $row->temp_asset_cd != $row->parent_asset_cd) {

                if (!isset($projectsUpgrade[$proj]['new_existing'][$typeKey])) {
                    $projectsUpgrade[$proj]['new_existing'][$typeKey] = [
                        'asset_plan_id' => $row->id,
                        'name' => $typeName,
                        'count' => 0,
                        'asset_name' => $row->temp_asset_name,
                        'parent' => $row->parent_asset_cd,
                        'parent_asset_cd' => $row->parent_asset_cd ?? null,
                        'new_asset_length' => $row->new_asset_length ?? null,
                        'is_asset_exist_in_main_table' => $is_asset_exist_in_main_table
                    ];
                }

                $projectsUpgrade[$proj]['new_existing'][$typeKey]['count'] += $count;
            }
        }


        foreach ($rowsMaintenance as $row) {

            $proj = $row->project_cd;

            if (!isset($projectsMaintenance[$proj])) {
                $projectsMaintenance[$proj] = [
                    'info' => [
                        'project_cd' => $row->project_cd,
                        'dept_cd' => $row->owner_dept_cd,
                        'project_name' => $row->project_name,
                        'division' => $row->division_name,
                        'sub_division' => $row->sub_div_name,
                        'division_cd' => $row->division_cd,
                        'sub_division_cd' => $row->sub_division_cd,
                        'start_date' => $row->project_start_date,
                        'end_date' => $row->project_end_date,
                    ],
                    'redefine' => []
                ];
            }

            // Each row = 1 redefine
            $projectsMaintenance[$proj]['redefine'][] = [
                'asset_plan_id' => $row->id,
                'name' => $row->sub_assets_descr,
                'asset_name' => $row->temp_asset_name,
                'asset_cd' => $row->maintained_asset_cd,
                'parent' => $row->parent_asset_cd
            ];
        }

        Log::info("projects Upgrade Final JSON : " . json_encode($projectsUpgrade));
        Log::info("projects Maintenance Final JSON : " . json_encode($projectsMaintenance));
        Log::info("projects New Final JSON : " . json_encode($projects));
        return view('pms.assetPlan.new_asset_summary', compact('projects', 'projectsUpgrade', 'projectsMaintenance'));
    }
}
