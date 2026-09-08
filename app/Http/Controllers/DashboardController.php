<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AssetMasterDivision;
use App\Models\AssetMasterRoadCategory;
use App\Models\AssetMasterSubDivision;
use App\Models\Mechanical\AssetMasterFuelType;
use App\Models\Mechanical\AssetMasterVehicleCondition;
use App\Models\Mechanical\AssetMasterVehicleType;
use App\Models\Mechanical\Master\AssetMasterEquipmentCondition;
use Exception;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }


    public function getCachedGeojsonData()
    {
        Log::info("Inside getCachedGeojsonData");
        $cacheKey = 'geojson_data';
        if (!Cache::has('geojson_data')) {
            Log::info("GeoJson Data for All State Caching In Memory");
            // Cache::remember($cacheKey, 60 * 60 * 24, function () {
            //     $rootPath = config('filesystems.disks.external.root');
            //     $geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/FINAL_GEO_JSON_FILE/' . 'all_nagaland_roads.geojson';
            //     // Load the GeoJSON file from storage
            //     $geojsonData = File::get($geojson_file_path);

            //     // Return the GeoJSON data (it will be cached)
            //     return $geojsonData;
            // });
            $rootPath = config('filesystems.disks.external.root');
            $geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/FINAL_GEO_JSON_FILE/' . 'all_nagaland_roads.geojson';
            // Load the GeoJSON file from storage
            $geojsonData = File::get($geojson_file_path);
            $compressedData = gzcompress($geojsonData, 9); // Compress the data
            $base64Encoded = base64_encode($compressedData);
            Cache::put("geojson_data", $base64Encoded, now()->addMinutes(1440));
        } else {
            Log::info("GeoJson Data Already Cached");
        }
    }

    public function getDashboard()
    {
        $user = Auth::user();
        $roadDetails = DB::table('asset_road_details')
            ->select('rd_system_id', 'rd_name')
            ->where('road_type', 'SR')
            ->orderBy("division_cd")
            ->orderBy("rd_category_cd")
            ->get();
        // $roadDetails = [];
        $division_dtls = AssetMasterDivision::query()
            ->where('dept_cd', '14')
            ->orderBy('division_name')
            ->get();
        $roadAbstractDetails = DB::table('asset_road_details')
            ->select(
                'asset_road_details.rd_category_cd',
                'asset_master_road_category.rd_catg_descr',
                DB::raw('count(asset_road_details.rd_system_id) as road_count'),
                DB::raw('sum(asset_road_details.road_length) as road_length')
            )
            ->where('road_type', 'SR')
            ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
            ->groupBy('asset_road_details.rd_category_cd', 'asset_master_road_category.rd_catg_descr')
            ->get();
        Log::info($roadAbstractDetails);
        // $roadAbstractDetails = [];
        $allStateDataSetResult = ["status" => False, "all_states_geojson_data" => "No Data Available"];

        // $geojsonData = Cache::get('geojson_data');
        // if ($geojsonData == null) {
        //     $this->getCachedGeojsonData();
        //     $geojsonData = Cache::get('geojson_data');
        // }
        // Log::info('Size of cached data: ' . strlen(serialize($geojsonData)) . ' bytes');


        // Storage::disk('public')->put('geojson/roads.geojson', json_encode($geojsonData));
        // $allStateDataSetResult = ["status" => True, "all_states_geojson_data" => $data];
        return view(
            'admin.dashboard',
            compact(
                'roadDetails',
                "division_dtls",
                'roadAbstractDetails',
                'allStateDataSetResult'
            )
        );
    }

    public function getDashboardNH()
    {
        $user = Auth::user();
        $roadDetails = DB::table('asset_road_details')
            ->select('rd_system_id', 'rd_name')
            ->where('road_type', 'NH')
            ->get();
        $division_dtls = AssetMasterDivision::query()
            ->where('dept_cd', '3')
            ->orderBy('division_name')
            ->get();
        $roadAbstractDetails = DB::table('asset_road_details')
            ->select(
                'asset_road_details.rd_category_cd',
                'asset_master_road_category.rd_catg_descr',
                DB::raw('count(asset_road_details.rd_system_id) as road_count'),
                DB::raw('sum(asset_road_details.road_length) as road_length')
            )
            ->where('road_type', 'NH')
            ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
            ->groupBy('asset_road_details.rd_category_cd', 'asset_master_road_category.rd_catg_descr')
            ->get();
        $allStateDataSetResult = ["status" => False, "all_states_geojson_data" => "No Data Available"];
        $this->getCachedGeojsonData();
        $data = Cache::get('geojson_data');
        if ($data) {
            $allStateDataSetResult = ["status" => True, "all_states_geojson_data" => $data];
        }
        return view(
            'admin.dashboardNH',
            compact(
                'roadDetails',
                "division_dtls",
                'roadAbstractDetails',
                'allStateDataSetResult'
            )
        );
    }
    public function getHousingDashboard()
    {
        $allApprovedBuildingDetails = DB::table('buildings.asset_building_details as bld')
            ->select(
                'bld.building_system_cd',
                'bld.building_type_cd',
                'bld.division_cd',
                'bld.sub_division_cd',
                'bld.bld_qtr_name',
                'bld.qtr_no',
                'bld.lat',
                'bld.lon',
                'bld_type.building_type_descr'
            )
            ->leftJoin('buildings.asset_master_building_types as bld_type', 'bld.building_type_cd', '=', 'bld_type.building_type_cd')
            ->get()->toArray();


        $buildingSummaries = DB::table('buildings.asset_building_details as bld')
            ->select(
                'bld.building_type_cd',
                'bld_type.building_type_descr',
                DB::raw('COUNT(bld.building_system_cd) AS building_count'),
                DB::raw('SUM(bld.plinth_area) AS plinth_area')
            )
            ->leftJoin('buildings.asset_master_building_types as bld_type', 'bld.building_type_cd', '=', 'bld_type.building_type_cd')
            ->groupBy('bld.building_type_cd', 'bld_type.building_type_descr')
            ->get();

        $division_dtls = AssetMasterDivision::query()
            ->where('dept_cd', '6')
            ->orderBy('division_name')
            ->get();
        $sub_division_dtls = AssetMasterSubDivision::query()
            ->where('dept_cd', '6')
            ->orderBy('sub_div_name')
            ->get();

        $bld_class_master = DB::table('buildings.asset_master_building_class')
            ->select('building_class_cd', 'building_class_descr')

            ->orderBy('building_class_descr', 'asc')
            ->get();

        $bld_catg_master = DB::table('buildings.asset_master_building_category')
            ->select('building_catg_cd', 'building_catg_descr')

            ->orderBy('building_catg_descr', 'asc')
            ->get();

        $bld_loc_master = DB::table('buildings.asset_master_building_locations')
            ->select('location_cd', 'location_name', 'division_cd', 'sub_division_cd', 'building_class_cd')
            ->orderBy('location_name', 'asc')
            ->get();

        $bld_type_master = DB::table('buildings.asset_master_building_types')
            ->select('building_type_cd', 'building_type_descr', 'building_class_cd')
            ->orderBy('building_type_descr', 'asc')
            ->get();
        return view(
            'admin.dashboardHousing',
            compact(
                'allApprovedBuildingDetails',
                'buildingSummaries',
                "division_dtls",
                "sub_division_dtls",
                "bld_class_master",
                "bld_catg_master",
                "bld_loc_master",
                "bld_type_master"
            )
        );
    }

    public function getEquipDashboard()
    {
        $equipmentConditions = AssetMasterEquipmentCondition::all();
        $fuelTypes = AssetMasterFuelType::all();
        $vehTypes = AssetMasterVehicleType::all();
        $vehicleCondtions = AssetMasterVehicleCondition::all();
        $equipmentSummaries = DB::table('mechanicals.asset_mech_equipment_details as equip')
            ->select(
                'equip.equipment_type',
                'equipType.equipment_type_descr',
                DB::raw('COUNT(*) as equipment_count'),
            )
            ->leftJoin('mechanicals.asset_master_euipment_types as equipType', 'equip.equipment_type', '=', 'equipType.equipment_type_cd')
            ->groupBy('equip.equipment_type', 'equipType.equipment_type_descr')
            ->get();

        $vehicleSummaries = DB::table('mechanicals.asset_mech_vehicles_details as vehicle')
            ->select(
                'vehicle.vehicle_type',
                'vehType.veh_type_descr',
                DB::raw('COUNT(*) as equipment_count'),
            )
            ->leftJoin('mechanicals.asset_master_vehicle_types as vehType', 'vehicle.vehicle_type', '=', 'vehType.veh_type_cd')
            ->groupBy('vehicle.vehicle_type', 'vehType.veh_type_descr')
            ->get();
        return view(
            'admin.dashboardEquip',
            compact('equipmentSummaries', 'vehicleSummaries', 'equipmentConditions', 'fuelTypes', 'vehTypes')
        );
    }


    public function viewRoadsInMapToDelete()
    {
        $user = Auth::user();
        $roadDetails = DB::table('asset_road_details')
            ->select('rd_system_id', 'rd_name', 'rd_category_cd')
            ->where('road_type', 'SR')
            ->orderBy("rd_category_cd")
            ->get();
        $division_dtls = AssetMasterDivision::query()
            ->where('dept_cd', '14')
            ->orderBy('division_name')
            ->get();
        $catg_dtls = AssetMasterRoadCategory::query()
            ->orderBy('rd_catg_cd')
            ->get();
        $roadAbstractDetails = DB::table('asset_road_details')
            ->select(
                'asset_road_details.rd_category_cd',
                'asset_master_road_category.rd_catg_descr',
                DB::raw('count(asset_road_details.rd_system_id) as road_count'),
                DB::raw('sum(asset_road_details.road_length) as road_length')
            )
            ->where('road_type', 'SR')
            ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
            ->groupBy('asset_road_details.rd_category_cd', 'asset_master_road_category.rd_catg_descr')
            ->get();

        $allStateDataSetResult = ["status" => False, "all_states_geojson_data" => "No Data Available"];
        $this->getCachedGeojsonData();
        $data = Cache::get('geojson_data');
        if ($data) {
            $allStateDataSetResult = ["status" => True, "all_states_geojson_data" => $data];
        }
        return view(
            'admin.viewRoadsInMapToDelete',
            compact(
                'roadDetails',
                "division_dtls",
                "catg_dtls",
                'roadAbstractDetails',
                'allStateDataSetResult'
            )
        );
    }
}
