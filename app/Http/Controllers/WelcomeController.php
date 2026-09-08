<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\OfficeDetail;
use Illuminate\Http\Request;
use App\Models\DepartmentDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\AssetMasterRdType;
use App\Models\AssetMasterDivision;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class WelcomeController extends Controller
{

    public function getWelcomeDashBoard()
    {
        DB::enableQueryLog();
        try {
            $deptCount = DepartmentDetail::all()->count();
            $offCount = OfficeDetail::all()->count();
            $userCount = User::all()->count();
            $departmentDetails = DepartmentDetail::all();
            $distressDetails = DB::table('asset_road_distress_details AS dstrs')
                ->leftJoin('asset_master_distress_type AS dstrs_tp', 'dstrs_tp.distress_type_cd', '=', 'dstrs.distress_type_cd')
                ->leftJoin('asset_road_details AS rd_dtls', 'rd_dtls.rd_system_id', '=', 'dstrs.rd_system_id')
                ->select(
                    'dstrs.rd_distress_cd',
                    "dstrs.rd_system_id",
                    "dstrs.start_lat",
                    "dstrs.start_lon",
                    "dstrs.end_lat",
                    "dstrs.end_lon",
                    "dstrs.distress_type_cd",
                    "dstrs_tp.distress_type_descr",
                    "dstrs.date_of_occurance",
                    "dstrs.days_to_restore",
                    "dstrs.distress_remarks",
                    "rd_dtls.rd_name"
                )
                ->where('dstrs.is_published', "Y")
                ->where('dstrs.restored_status', "N")->get();
            // Log::info($distressDetails);
            $query = DB::getQueryLog();
            Log::info(end($query));
            $totalRoads = DB::table('asset_road_details')
                ->select(DB::raw('COUNT(DISTINCT rd_system_id) as total_road'))
                ->where('road_type', 'SR')
                ->first();

            $totalBuilding = DB::table('buildings.asset_building_details')
                ->select(DB::raw('COUNT(DISTINCT building_system_cd) as total_building'))
                ->first();

            $totalNH = DB::table('asset_road_details')
                ->select(DB::raw('COUNT(DISTINCT rd_system_id) as total_road'))
                ->where('road_type', 'NH')
                ->first();

            $totalEquipment = DB::table('mechanicals.asset_mech_equipment_details')
                ->select(DB::raw('COUNT(DISTINCT euipment_cd) as total_equipment'))
                ->first();

            $totalVehicle = DB::table('mechanicals.asset_mech_vehicles_details')
                ->select(DB::raw('COUNT(DISTINCT vehicle_asset_cd) as total_vehicles'))
                ->first();

            $totalRoadCount = $totalRoads->total_road;
            $totalNHCount = $totalNH->total_road;
            $totalBuildingCount = $totalBuilding->total_building;
            $totalEquipmentCount = $totalEquipment->total_equipment;
            $totalVehicleCount = $totalVehicle->total_vehicles;

            $roadCategoryCounts = DB::table('asset_road_details as a')
                ->join('asset_master_road_category as m', 'a.rd_category_cd', '=', 'm.rd_catg_cd')
                ->select('a.rd_category_cd', 'm.rd_catg_descr', DB::raw('COUNT(*) as count'))
                ->groupBy('m.rd_catg_descr')
                ->groupBy('a.rd_category_cd')
                ->where('a.rd_category_cd', '<>', 'NH')
                ->get();

            $buildingClassCounts = DB::table('buildings.asset_building_details as a')
                ->join('buildings.asset_master_building_class as bc', 'a.building_class_cd', '=', 'bc.building_class_cd')
                ->select('a.building_class_cd', 'bc.building_class_descr', DB::raw('COUNT(*) as count'))
                ->groupBy('bc.building_class_cd')
                ->groupBy('a.building_class_cd')
                ->get();


            $allStateDataSetResult = ["status" => False, "all_states_geojson_data" => "No Data Available"];
            // $this->getCachedGeojsonData();
            // $data = Cache::get('geojson_data');
            // if ($data) {
            //     $allStateDataSetResult = ["status" => True, "all_states_geojson_data" => $data];
            // }
            return view(
                'welcome',
                compact(
                    'deptCount',
                    'offCount',
                    'userCount',
                    'departmentDetails',
                    'distressDetails',
                    'totalRoadCount',
                    'roadCategoryCounts',
                    'totalNHCount',
                    'totalBuildingCount',
                    'totalEquipmentCount',
                    'totalVehicleCount',
                    'buildingClassCounts',
                    'allStateDataSetResult'
                )
            );
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
        }
    }

    public function getPrivacyPolicy()
    {
        try {
            return view('privacyPolicy');
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }


    public function roadByCatg($rd_catg)
    {
        DB::enableQueryLog();
        Log::info("Here is comes" . $rd_catg);
        $catg = $rd_catg;
        $catg_descr = "";
        $qry_rd_catg_descr = DB::table("asset_master_road_category")
            ->select("rd_catg_descr")
            ->where("rd_catg_cd", $catg)
            ->get()->first();
        if ($qry_rd_catg_descr)
            $catg_descr = $qry_rd_catg_descr->rd_catg_descr;

        $rdTypeMaster = AssetMasterRdType::all();
        $divMaster = AssetMasterDivision::where('dept_cd', 14)->get();
        try {
            $baseQuery = DB::table('asset_road_details')
                ->select(
                    'asset_road_details.rd_system_id',
                    'asset_road_details.rd_number',
                    'asset_road_details.rd_name',
                    'asset_road_details.road_length',
                    'asset_road_details.district_name',
                    'asset_road_details.division_name',
                    'asset_master_road_category.rd_catg_descr',
                    'asset_master_rd_type.rd_type_descr',
                    'asset_master_road_owner.owner_name'
                )
                ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                ->where('asset_road_details.road_type', '=', 'SR')
                ->orderBy('asset_road_details.division_name', 'desc')
                ->orderBy('asset_master_road_owner.owner_name', 'desc')
                ->orderBy('created_at', 'desc');
            $query = DB::getQueryLog();
            Log::info(end($query));
            if ($catg) {
                $baseQuery->where('asset_road_details.rd_category_cd', $catg);
            }
            $roadDetails = $baseQuery->get();
            // dd($roadDetails);
            // return $roadDetails;
            return view('getRoadByCatg', compact('roadDetails', 'catg', 'catg_descr', 'rdTypeMaster', 'divMaster'));
        } catch (Exception $e) {
            Log::error("Error message: " . $e->getMessage());
        }
    }

    public function housingByClass($bld_cls_cd)
    {
        DB::enableQueryLog();
        $cls_cd = $bld_cls_cd;
        $cls_descr = "";

        $qry_bld_class_descr = DB::table("buildings.asset_master_building_class")
            ->select("building_class_descr")
            ->where("building_class_cd", $bld_cls_cd)
            ->get()->first();
        if ($qry_bld_class_descr)
            $cls_descr = $qry_bld_class_descr->building_class_descr;

        $rdTypeMaster = AssetMasterRdType::all();
        $divMaster = AssetMasterDivision::where('dept_cd', 14)->get();
        try {

            $baseQuery = DB::table('buildings.asset_building_details as bld')
                ->select(
                    'bld.building_system_cd',
                    'bld.building_type_cd',
                    'bld.construction_year',
                    'bld.asset_name',
                    'bld.building_class_cd',
                    'bld.bld_qtr_name',
                    'bld.qtr_no',
                    'bld.bld_catg',
                    'bld.plinth_area',
                    'bld.has_water_supply',
                    'bld.has_electricity',
                    'bld.has_sanitary',
                    'bld.occupant_name',
                    'bld.occupant_dept_cd',
                    'bld.building_location_cd',
                    'bld_tp_m.building_type_descr',
                    'bld.division_cd',
                    'bld.lat',
                    'bld.lon',
                    'bld.sub_division_cd',
                    'bld_cls_m.building_class_descr',
                    'bld_catg_m.building_catg_descr',
                    'bld_ocpnt_dept_m.dept_descr',
                    'bld_loc_m.location_name',
                    'div_m.division_name',
                    'sub_div_m.sub_div_name'
                )
                ->leftJoin('buildings.asset_master_building_types as bld_tp_m', 'bld.building_type_cd', '=', 'bld_tp_m.building_type_cd')
                ->leftJoin('buildings.asset_master_building_class as bld_cls_m', 'bld.building_class_cd', '=', 'bld_cls_m.building_class_cd')
                ->leftJoin('buildings.asset_master_building_category as bld_catg_m', 'bld.bld_catg', '=', 'bld_catg_m.building_catg_cd')
                ->leftJoin('public.asset_master_dept_of_state as bld_ocpnt_dept_m', 'bld.occupant_dept_cd', '=', 'bld_ocpnt_dept_m.id')
                ->leftJoin('buildings.asset_master_building_locations as bld_loc_m', 'bld.building_location_cd', '=', 'bld_loc_m.location_cd')
                ->leftJoin('asset_master_divisions as div_m', 'bld.division_cd', '=', 'div_m.division_cd')
                ->leftJoin('asset_master_sub_divisions as sub_div_m', 'bld.sub_division_cd', '=', 'sub_div_m.sub_div_cd')
                ->orderBy('bld.building_location_cd', 'desc')
                ->orderBy('bld.division_cd', 'desc')
                ->orderBy('bld.sub_division_cd', 'desc')
                ->orderBy('bld.asset_name', 'desc')
                ->orderBy('bld.created_at', 'desc');

            $baseQuery->where('bld.building_class_cd', $cls_cd);

            $bldDetails = $baseQuery->get();
            $query = DB::getQueryLog();
            Log::info(end($query));
            return view('getBuildingsByClass', compact('bldDetails', 'cls_cd', 'cls_descr'));
        } catch (Exception $e) {
            Log::error("Error message: " . $e->getMessage());
        }
    }

    public function roadNHByCatg()
    {
        DB::enableQueryLog();

        $catg = "NH";
        Log::info("Here is comes Wirh Road Catg:  " . $catg);
        $catg_descr = "National Highway";
        $rdTypeMaster = AssetMasterRdType::all();
        $divMaster = AssetMasterDivision::where('dept_cd', 3)->get();
        try {

            $baseQuery = DB::table('asset_road_details')
                ->select(
                    'asset_road_details.rd_system_id',
                    'asset_road_details.rd_number',
                    'asset_road_details.rd_name',
                    'asset_road_details.road_length',
                    'asset_road_details.district_name',
                    'asset_road_details.division_name',
                    'asset_master_road_category.rd_catg_descr',
                    'asset_master_rd_type.rd_type_descr',
                    'asset_master_road_owner.owner_name'
                )
                ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                ->where('asset_road_details.road_type', '=', 'NH')
                ->orderBy('asset_road_details.division_name', 'desc')
                ->orderBy('asset_master_road_owner.owner_name', 'desc')
                ->orderBy('created_at', 'desc');
            if ($catg) {
                $baseQuery->where('asset_road_details.rd_category_cd', $catg);
            }
            $roadDetails = $baseQuery->get();

            $query = DB::getQueryLog();
            Log::info(end($query));
            return view('getRoadByCatg', compact('roadDetails', 'catg', 'catg_descr', 'rdTypeMaster', 'divMaster'));
        } catch (Exception $e) {
        }
    }

    public function loadRoadByCatg(Request $request)
    {
        DB::enableQueryLog();
        $catg = $request->category;
        $baseQuery = DB::table('asset_road_details')
            ->select(
                'asset_road_details.rd_system_id',
                'asset_road_details.rd_number',
                'asset_road_details.rd_name',
                'asset_road_details.road_length',
                'asset_road_details.district_name',
                'asset_road_details.division_name',
                'asset_master_road_category.rd_catg_descr',
                'asset_master_rd_type.rd_type_descr',
                'asset_master_road_owner.owner_name'
            )
            ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
            ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
            ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
            ->where('asset_road_details.road_type', '=', 'SR')
            ->orderBy('asset_road_details.division_name', 'desc')
            ->orderBy('asset_master_road_owner.owner_name', 'desc')
            ->orderBy('created_at', 'desc');
        if ($catg) {
            $baseQuery->where('asset_road_details.rd_category_cd', $catg);
        }
        $roadDetails = $baseQuery->get();


        $query = DB::getQueryLog();
        Log::info(end($query));
        Log::info("Test xxxx");
        if ($roadDetails) {
            return response()->json([
                'status' => 200,
                'message' => 'Road data fetched successfully!',
                'result' => $roadDetails
            ]);
        } else {
            return response()->json([
                'status' => 204,
                'message' => 'Road details not available!',
                'result' => null
            ]);
        }
    }

    public function getCachedGeojsonData()
    {
        Log::info("Inside getCachedGeojsonData");
        $result = ["status" => False, "all_states_geojson_data" => "No Data Available"];
        $cacheKey = 'geojson_data';
        if (!Cache::has('geojson_data')) {
            Log::info("GeoJson Data Caching In Memory");
            // Cache::remember($cacheKey, 60 * 60 * 24, function () {
            //     $rootPath = config('filesystems.disks.external.root');
            //     $geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/FINAL_GEO_JSON_FILE/' . 'all_nagaland_roads.geojson';
            //     // Load the GeoJSON file from storage
            //     $geojsonData = File::get($geojson_file_path);
            //     $compressedData = base64_encode(gzcompress($geojsonData, 9));
            //     // Return the GeoJSON data (it will be cached)
            //     return $compressedData;
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
    public function getAllStatesRoadsGeoJsonData()
    {
        $result = ["status" => False, "all_states_geojson_data" => "No Data Available"];
        try {
            // $rootPath = config('filesystems.disks.external.root');
            // $geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/FINAL_GEO_JSON_FILE/' . 'all_nagaland_roads.geojson';
            // $data = File::get($geojson_file_path);

            // if ($data) {
            //     $result = ["status" => True, "all_states_geojson_data" => $data];
            // }

            $data = Cache::get('geojson_data');
            if ($data == null) {
                $this->getCachedGeojsonData();
                $data = Cache::get('geojson_data');
            }

            if ($data) {
                $result = ["status" => True, "all_states_geojson_data" => $data];
            }
            // Log::info(json_encode($result));

        } catch (Exception $e) {
            Log::error("Error In Getting all States GeoJson Data from Cache Memory :" . $e);
            $result = ["status" => False, "all_states_geojson_data" => "Some technical Issue!! Please Try after some Time"];
        }
        return response()->json($result);
    }

    public function getAllSHNHMDRRoadsGeoJsonData()
    {
        $result = ["status" => False, "all_sh_nh_mdr_geojson_data" => "No Data Available"];
        try {
            $data = Cache::get('sh_nh_mdr_geojson_data');
            if ($data == null) {
                $this->getSHNHMDRCachedGeojsonData();
                $data = Cache::get('sh_nh_mdr_geojson_data');
            }

            if ($data) {
                $result = ["status" => True, "all_sh_nh_mdr_geojson_data" => $data];
            }
            // Log::info(json_encode($result));

        } catch (Exception $e) {
            Log::error("Error In Getting all sh nh mdr GeoJson Data from Cache Memory :" + $e);
            $result = ["status" => False, "all_sh_nh_mdr_geojson_data" => "Some technical Issue!! Please Try after some Time"];
        }
        return response()->json($result);
    }
    public function getAllStatesRoadsGeoJsonDataWithLazyLoading(Request $request)
    {
        $filteredGeoJson = ['type' => 'FeatureCollection', "status" => False, 'features' => null, "all_states_geojson_data" => "No Data Available"];
        try {
            $rootPath = config('filesystems.disks.external.root');
            $geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/FINAL_GEO_JSON_FILE/' . 'all_nagaland_roads.geojson';
            $data = json_decode(File::get($geojson_file_path));

            if ($data) {
                // $result = ["status" => True, "all_states_geojson_data" => $data];
                $request->validate([
                    'north' => 'required|numeric',
                    'south' => 'required|numeric',
                    'east' => 'required|numeric',
                    'west' => 'required|numeric',
                ]);
                // Get bounding box parameters from the request
                $north = $request->input('north');
                $south = $request->input('south');
                $east = $request->input('east');
                $west = $request->input('west');
                $filteredFeatures = array_filter($data->features, function ($feature) use ($north, $south, $east, $west) {
                    $coordinates = $feature->geometry->coordinates;

                    // Handle different geometry types (e.g., Point, LineString, Polygon)
                    if ($feature->geometry->type === 'LineString') {
                        foreach ($coordinates as $point) {
                            $lng = $point[0]; // Longitude
                            $lat = $point[1]; // Latitude

                            // If any point in the LineString is within the bounding box, include this feature
                            if ($lat >= $south && $lat <= $north && $lng >= $west && $lng <= $east) {
                                return true;
                            }
                        }
                    }

                    // For LineString and Polygon geometries, you may need more complex logic
                    // For simplicity, this example handles Points
                    return false;
                });

                $filteredGeoJson = [
                    'type' => 'FeatureCollection',
                    'features' => array_values($filteredFeatures), // reindex the array
                    "status" => True

                ];

                return response()->json($filteredGeoJson);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            $filteredGeoJson = [
                "status" => False,
                "all_states_geojson_data" => "Some technical Issue!! Please Try after some Time"
            ];
        }
        return response()->json($filteredGeoJson);
    }


    public function getDivisionsRoadsGeoJsonData(Request $request)
    {
        DB::enableQueryLog();
        $result = ["status" => False, "division_geojson_data" => "No Data Available"];
        try {
            $division_cd = $request->division_cd;
            $rootPath = config('filesystems.disks.external.root');
            $geojson_file_path = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/DIVISION_WISE_FINAL_GEO_JSON_FILES/' . $division_cd . '.geojson';
            $data = File::get($geojson_file_path);

            $div_lat = 26.094757374299146;
            $div_lon = 94.58979407214116;
            $division_dtls = DB::table("asset_master_divisions")
                ->select("lat", "lng")
                ->where('division_cd', $division_cd)
                ->get()->first();
            if ($division_dtls) {
                $div_lat = $division_dtls->lat;
                $div_lon = $division_dtls->lng;
            }

            $query = DB::getQueryLog();
            Log::info($query);
            if ($data) {
                $result = ["status" => True, "division_geojson_data" => $data, "div_lat" => $div_lat, "div_lon" => $div_lon];
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            $result = ["status" => False, "division_geojson_data" => "Some technical Issue!! Please Try after some Time"];
        }
        return $result;
    }

    public function getDraftedRoadsWithDivisionRoads(Request $request)
    {
        $result = ["status" => False, "coordinates" => "No Data Found To Display in Map"];
        try {
            $road_id = $request->road_id;
            $division_cd = null;
            $division_name = null;
            $draftRoadsGeoJsonFilePath = null;
            $final_geojson_file_path_with_file_name = null;
            $draft_road_data = null;
            $approved_divisions_road_data = null;
            $lat = null;
            $lng = null;
            $rootPath = config('filesystems.disks.external.root');
            $objRoadDetails = DB::table('asset_road_details_draft as rd')
                ->select("rd.division_cd", "rd.division_name", "kmlFile.geojson_file_path", "mdiv.lat", "mdiv.lng")
                ->leftJoin('asset_road_document_kml_file_details as kmlFile', 'kmlFile.rd_system_id', '=', 'rd.rd_system_id')
                ->leftJoin('asset_master_divisions as mdiv', 'mdiv.division_cd', '=', 'rd.division_cd')
                ->where("rd.rd_system_id", $road_id)
                ->where("kmlFile.rd_system_id", $road_id)
                ->get()->first();
            if ($objRoadDetails) {
                $division_cd = $objRoadDetails->division_cd;
                $division_name = $objRoadDetails->division_name;
                $draftRoadsGeoJsonFilePath = $objRoadDetails->geojson_file_path;
                $lat = $objRoadDetails->lat;
                $lng = $objRoadDetails->lng;
            }
            if ($division_cd != null) {
                $final_geojson_file_path_with_file_name = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/DIVISION_WISE_FINAL_GEO_JSON_FILES/' . $division_cd . '.geojson';
                Log::info("Approved geojson data fetching from Division " . $division_name . " File");
            }
            // else {
            //     $draftRoadsGeoJsonFilePath = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/ROAD_WISE_GEO_JSON_FILES/' . $road_id . '.geojson';
            //     $final_geojson_file_path_with_file_name = $rootPath . '/' . config('customconfigpath.DOC_ROOT_FOLDER_NAME') . '/' . 'ROADS/FINAL_GEO_JSON_FILE/all_nagaland_roads.geojson';
            //     Log::info("Approved geojson data could not find from division. Hence Data will be displayed from All States Geo Json Data!!!!");
            // }

            $draft_road_data = File::get($draftRoadsGeoJsonFilePath);
            if ($final_geojson_file_path_with_file_name)
                $approved_divisions_road_data = File::get($final_geojson_file_path_with_file_name);
            $result = [
                "status" => True,
                "approved_geojson_data" => $approved_divisions_road_data,
                "draft_road_geojson_data" => $draft_road_data,
                "div_lat" => $lat,
                "div_lng" => $lng,
            ];
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            $result = ["status" => False, "coordinates" => "Some technical Issue!! Please Try after some Time"];
        }

        return $result;
    }
}
