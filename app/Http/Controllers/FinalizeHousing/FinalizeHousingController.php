<?php

namespace App\Http\Controllers\FinalizeHousing;

use App\Http\Controllers\Controller;
use App\Models\Building\Master\AssetMasterBoundaryType;
use App\Models\Building\Master\AssetMasterBuildingBeamType;
use App\Models\Building\Master\AssetMasterBuildingColumnType;
use App\Models\Building\Master\AssetMasterBuildingCondition;
use App\Models\Building\Master\AssetMasterBuildingFoundationType;
use App\Models\Building\Master\AssetMasterBuildingSlabType;
use App\Models\Building\Master\AssetMasterBuildingStaircaseType;
use App\Models\Building\Master\AssetMasterBuildingType;
use App\Models\Building\Master\AssetMasterBuildingUsePurpose;
use App\Models\Building\Master\AssetMasterBuildingWallType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Exception;

class FinalizeHousingController extends Controller
{
    private function masterData(): array
    {
        return [
            'boundaryTypes'             => AssetMasterBoundaryType::all(),
            'buildingConditions'        => AssetMasterBuildingCondition::all(),
            'buildingFoundationTypes'   => AssetMasterBuildingFoundationType::all(),
            'buildingTypes'             => AssetMasterBuildingType::all(),
            'buildingUsePurposes'       => AssetMasterBuildingUsePurpose::all(),
            'buildingWallTypes'         => AssetMasterBuildingWallType::all(),
            'buildingBeamTypes'         => AssetMasterBuildingBeamType::all(),
            'buildingColumnTypes'       => AssetMasterBuildingColumnType::all(),
            'buildingSlabTypes'         => AssetMasterBuildingSlabType::all(),
            'buildingStaircaseTypes'    => AssetMasterBuildingStaircaseType::all(),
        ];
    }

    public function index()
    {
            $user = Auth::user();
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
            // end

            $buildingDetailsBaseQuery = DB::table('buildings.asset_building_details_draft as building')
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
                // ->where('created_at_office_cd', '=', $office_cd)
                ->where('sent_for_finalize', '=', 'Y')
                ->orderByDesc('building.updated_at');

            if ($users_office_type_cd == 'HQ') {
                $buildingDetails = $buildingDetailsBaseQuery->get();
            }

            if ($users_office_type_cd == 'ZO') {
                $ZOOffices = [];
                // Get all the offices under this zone
                $offices = DB::table('office_details')
                    ->where('zone_cd', $zone_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $ZOOffices[] = $item->id;
                }
                $buildingDetails = $buildingDetailsBaseQuery
                    ->whereIn('created_at_office_cd', $ZOOffices)
                    ->get();
            }

            if ($users_office_type_cd == 'CO') {
                $COOffices = [];
                // Get all the offices under this zone
                $offices = DB::table('office_details')
                    ->where('circle_cd', $circle_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $COOffices[] = $item->id;
                }
                $buildingDetails = $buildingDetailsBaseQuery
                    ->whereIn('created_at_office_cd', $COOffices)
                    ->get();
            }

            if ($users_office_type_cd == 'DO') {
                $DOOffices = [];
                // Get all the offices under this zone
                $offices = DB::table('office_details')
                    ->where('division_cd', $division_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $DOOffices[] = $item->id;
                }
                $buildingDetails = $buildingDetailsBaseQuery
                    ->whereIn('created_at_office_cd', $DOOffices)
                    ->get();
            }

            if ($users_office_type_cd == 'SDO') {
                $SDOffices = [];
                // Get all the offices under this zone
                $offices = DB::table('office_details')
                    ->where('sub_division_cd', $sub_division_cd)
                    ->where('department_id', session('user_dept_cd'))
                    ->select('id')->get();
                foreach ($offices as $item) {
                    $SDOffices[] = $item->id;
                }
                $buildingDetails = $buildingDetailsBaseQuery
                    ->whereIn('created_at_office_cd', $SDOffices)
                    ->get();
            }

			return view('building.finalize', array_merge(
            $this->masterData(),
            compact('user', 'buildingDetails')
        ));										 
    }

    public function acceptHousing(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $buildingID = $request->id;
                $approvedBy = Auth::user()->id;
                $currentTime = now();
                $status = DB::table('buildings.asset_building_details')->insertUsing([
                    'building_system_cd',
                    'building_type_cd',
                    'construction_year',
                    'created_by',
                    'created_at_office_cd',
                    'created_at',
                    'updated_at',
                    'division_cd',
                    'sub_division_cd',
                    'asset_name',
                    'building_class_cd',
                    'bld_qtr_name',
                    'qtr_no',
                    'bld_catg',
                    'plinth_area',
                    'construction_cost',
                    'has_water_supply',
                    'has_electricity',
                    'has_sanitary',
                    'occupant_name',
                    'occupant_dept_cd',
                    'remark',
                    'building_location_cd',
                    'lat',
                    'lon',
                    'dist_cd',
                    'is_maintained_by_npwd',
                    'building_access_type_cd',
                    'security_fenching_type_cd',
                    'asset_owning_dept_cd',
                    'plot_area',
                    'approved_by',
                    'approved_at'
                ], function ($query) use ($buildingID, $currentTime, $approvedBy) {
                    $query->from('buildings.asset_building_details_draft')
                        ->where('building_system_cd', '=', $buildingID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->select(
                            'building_system_cd',
                            'building_type_cd',
                            'construction_year',
                            'created_by',
                            'created_at_office_cd',
                            'created_at',
                            'updated_at',
                            'division_cd',
                            'sub_division_cd',
                            'asset_name',
                            'building_class_cd',
                            'bld_qtr_name',
                            'qtr_no',
                            'bld_catg',
                            'plinth_area',
                            'construction_cost',
                            'has_water_supply',
                            'has_electricity',
                            'has_sanitary',
                            'occupant_name',
                            'occupant_dept_cd',
                            'remark',
                            'building_location_cd',
                            'lon',
                            'lat',
                            'dist_cd',
                            'is_maintained_by_npwd',
                            'building_access_type_cd',
                            'security_fenching_type_cd',
                            'asset_owning_dept_cd',
                            'plot_area',
                            DB::raw("'$approvedBy' as approved_by"),
                            DB::raw("'$currentTime' as approved_at")
                        );
                });

                if ($status > 0) {
                    DB::table('buildings.asset_building_details_draft')
                        ->where('building_system_cd', '=', $buildingID)
                        ->where('sent_for_finalize', '=', 'Y')
                        ->delete();
                    return response()->json([
                        'status' => 200,
                        'message' => 'Finalized all data successfully!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Data not available to finalize!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unothorized Access'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }

    public function rejectHousing(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $buildingID = $request->id;

                $status = DB::table('buildings.asset_building_details_draft')
                    ->where('sent_for_finalize', 'Y')
                    ->where('building_system_cd', $buildingID)
                    ->update([
                        'is_rejected' => 'Y',
                        'reason_of_rejection' => $request->reason,
                        'date_of_rejection' => Carbon::now(),
                        'rejected_by' => Auth::user()->id,
                        'sent_for_finalize' => 'N',
                        'updated_at' => Carbon::now()
                    ]);

                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Building data rejected!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Failed to reject building data!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unothorized Access'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal server error!'
            ]);
        }
    }
}
