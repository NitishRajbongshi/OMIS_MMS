<?php

namespace App\Http\Controllers\MIS\Housing;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class HousingMISController extends Controller
{
    public function searchBuilding(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $division = $request->input('division');
                // $subDivision = $request->input('subDivision');
                $type = $request->input('type');
                $class = $request->input('class');
                $department = $request->input('department');
                $maintain = $request->input('maintain');
                // $category = $request->input('category');
                // $constructionYear = $request->input('construction_year');
                // $water = $request->input('water');
                // $electricity = $request->input('electricity');
                // $sanitary = $request->input('sanitary');
                $baseQuery = DB::table('buildings.asset_building_details as building')
                    ->select(
                        'building.building_system_cd',
                        'building.building_type_cd',
                        'building.construction_year',
                        'building.created_by',
                        'building.created_at_office_cd',
                        'building.asset_name',
                        'building.is_maintained_by_npwd',
                        'building.building_class_cd',
                        'building.bld_qtr_name',
                        'building.qtr_no',
                        'building.bld_catg',
                        'building.plinth_area',
                        'building.plot_area',
                        'building.construction_cost',
                        'building.has_water_supply',
                        'building.has_electricity',
                        'building.has_sanitary',
                        'building.occupant_name',
                        'building.occupant_dept_cd',
                        'building.asset_owning_dept_cd',
                        'building.remark',
                        'building.building_location_cd',
                        'building.lat',
                        'building.lon',
                        'buildingType.building_type_descr',
                        'buildingClass.building_class_descr',
                        'buildingCtg.building_catg_descr',
                        'buildingLocation.location_name',
                        'building.building_access_type_cd',
                        'buildingAccess.access_type_descr',
                        'building.security_fenching_type_cd',
                        'buildingFencing.fenching_type_descr',
                        'buildingDept.dept_name as department_name',
                        'buildingOwningDept.dept_name as owning_dept_name'
                    )
                    ->leftJoin('buildings.asset_master_building_types as buildingType', 'building.building_type_cd', '=', 'buildingType.building_type_cd')
                    ->leftJoin('buildings.asset_master_building_class as buildingClass', 'building.building_class_cd', '=', 'buildingClass.building_class_cd')
                    ->leftJoin('buildings.asset_master_building_category as buildingCtg', 'building.bld_catg', '=', 'buildingCtg.building_catg_cd')
                    ->leftJoin('buildings.asset_master_building_locations as buildingLocation', 'building.building_location_cd', '=', 'buildingLocation.location_cd')
                    ->leftJoin('public.asset_master_dept_of_state as buildingDept', 'building.occupant_dept_cd', '=', 'buildingDept.id')
                    ->leftJoin('buildings.asset_master_building_access_types as buildingAccess', 'building.building_access_type_cd', '=', 'buildingAccess.access_type_cd')
                    ->leftJoin('buildings.asset_master_building_security_fenching_types as buildingFencing', 'building.security_fenching_type_cd', '=', 'buildingFencing.fenching_type_cd')
                    ->leftJoin('public.asset_master_dept_of_state as buildingOwningDept', 'building.asset_owning_dept_cd', '=', 'buildingOwningDept.id')
                    ->orderByDesc('building.updated_at');

                if ($division != 'null') {
                    $baseQuery->where('building.division_cd', $division);
                }
                // if ($subDivision != 'null') {
                //     $baseQuery->where('building.sub_division_cd', $subDivision);
                // }
                if ($type != 'null') {
                    $baseQuery->where('building.building_type_cd', $type);
                }
                if ($class != 'null') {
                    $baseQuery->where('building.building_class_cd', $class);
                }
                if ($department != 'null') {
                    $baseQuery->where('building.asset_owning_dept_cd', $department);
                }
                if ($maintain != 'null') {
                    $baseQuery->where('building.is_maintained_by_npwd', $maintain);
                }
                // if ($category != 'null') {
                //     $baseQuery->where('building.bld_catg', $category);
                // }
                // if ($constructionYear != 'null') {
                //     $baseQuery->where('building.construction_year', $constructionYear);
                // }
                // if ($water != 'null') {
                //     $baseQuery->where('building.has_water_supply', $water);
                // }
                // if ($electricity != 'null') {
                //     $baseQuery->where('building.has_electricity', $electricity);
                // }
                // if ($sanitary != 'null') {
                //     $baseQuery->where('building.has_sanitary', $sanitary);
                // }

                $buildingDetails = $baseQuery->get();
                Log::info($buildingDetails);

                if ($buildingDetails) {
                    if ($buildingDetails->count() == 0) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Data not available!',
                            'result' => $buildingDetails
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Building data fetched successfully!',
                            'result' => $buildingDetails
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Building details not available!',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }
}
