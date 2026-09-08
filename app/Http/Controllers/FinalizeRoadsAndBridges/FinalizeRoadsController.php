<?php

namespace App\Http\Controllers\FinalizeRoadsAndBridges;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
class FinalizeRoadsController extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
        Log::info("Finalize Road Controller");
        $this->middleware("auth");
    }


    public function getSHNHMDRCachedGeojsonData()
    {
        Log::info("Inside getSHNHMDRCachedGeojsonData");
        $cacheKey = 'sh_nh_mdr_geojson_data';
        if (!Cache::has('sh_nh_mdr_geojson_data')) {
            Log::info("GeoJson Data for nh sh mdr roads Caching In Memory");
            // Cache::remember($cacheKey, 60 * 60 * 24, function () {
            //     $rootPath = config('filesystems.disks.external.root');
            //     $sh_nh_mdr_geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/FINAL_GEO_JSON_FILE/' . 'all_nh_sh_mdr_roads.geojson';
            //     // Load the GeoJSON file from storage
            //     $sh_nh_mdr_geojsonData = File::get($sh_nh_mdr_geojson_file_path);

            //     // Return the GeoJSON data (it will be cached)
            //     return $sh_nh_mdr_geojsonData;
            // });

            $rootPath = config('filesystems.disks.external.root');
            $sh_nh_mdr_geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/FINAL_GEO_JSON_FILE/' . 'all_nh_sh_mdr_roads.geojson';
            // Load the GeoJSON file from storage
            $geojsonData = File::get($sh_nh_mdr_geojson_file_path);
            $compressedData = gzcompress($geojsonData, 9); // Compress the data
            $base64Encoded = base64_encode($compressedData);
            Cache::put("sh_nh_mdr_geojson_data", $base64Encoded, now()->addMinutes(1440));
        } else {
            Log::info("GeoJson Data SH NH MDR Already Cached");
        }
    }
    public function index()
    {
        try {
            $user = Auth::user();
            $roadType = 'SR'; // consider the road type as SR
            if ($user->department == '3') {
                $roadType = 'NH'; // Switch to road type into NH
            }
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
            $baseQuery = DB::table('asset_road_details_draft as road_details')
                ->select(
                    'road_details.rd_system_id',
                    'road_details.rd_number',
                    'road_details.rd_name',
                    'road_details.road_length',
                    'road_details.division_cd',
                    'category.rd_catg_descr',
                    'type.rd_type_descr',
                    'owner.owner_name',
                    'div.division_name',
                    'pp.project_cd',//saiful 20-04-2026
                    'pp.id'//saiful 20-04-2026 
                )
                ->leftJoin('asset_master_road_category as category', 'road_details.rd_category_cd', '=', 'category.rd_catg_cd')
                ->leftJoin('asset_master_rd_type as type', 'road_details.rd_type_cd', '=', 'type.rd_type_cd')
                ->leftJoin('asset_master_road_owner as owner', 'road_details.rd_owner_cd', '=', 'owner.owner_cd')
                ->leftJoin('asset_master_divisions as div', 'road_details.division_cd', '=', 'div.division_cd')
                ->leftJoin('prt_project_asset_plan as pp', 'pp.id', '=', 'road_details.asset_plan_id')//saiful 20-04-2024
                ->where('road_details.road_type', '=', $roadType)
                ->where('road_details.sent_for_finalize', '=', 'Y')
                ->orderBy('road_details.updated_at', 'desc');
            if ($users_office_type_cd == 'HQ') {
                $roadDraftDetails = $baseQuery->get();
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
                $roadDraftDetails = $baseQuery->whereIn('road_details.road_created_at_office_cd', $ZOOffices)->get();
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
                $roadDraftDetails = $baseQuery->whereIn('road_details.road_created_at_office_cd', $COOffices)->get();
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
                $roadDraftDetails = $baseQuery->whereIn('road_details.road_created_at_office_cd', $DOOffices)->get();
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
                $roadDraftDetails = $baseQuery->whereIn('road_details.road_created_at_office_cd', $SDOffices)->get();
            }

            $shnhmdrDataSetResult = ["status" => False, "sh_nh_mdr_geojson_data" => "No Data Available"];


            // $shnhmdrgeojsondata = Cache::get('sh_nh_mdr_geojson_data');
            // if ($shnhmdrgeojsondata == null) {
            // 	$this->getSHNHMDRCachedGeojsonData();
            // 	$shnhmdrgeojsondata = Cache::get('sh_nh_mdr_geojson_data');
            // }
            // $shnhmdrDataSetResult = ["status" => True, "sh_nh_mdr_geojson_data" => $shnhmdrgeojsondata];
            return view('road.finalize.road', compact(
                'roadDraftDetails',
                'shnhmdrDataSetResult'
            ));
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function store(Request $request)
    {
        //
    }
}
