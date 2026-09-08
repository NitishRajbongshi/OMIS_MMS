<?php

namespace App\Http\Controllers\Building;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Building\AssetBuildingDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Building\AssetBuildingDetailsDraft;
use App\Models\Road\Master\AssetMasterDeptOfState;
use App\Models\Building\Master\AssetMasterBoundaryType;
use App\Models\Building\Master\AssetMasterBuildingAccessType;
use App\Models\Building\Master\AssetMasterBuildingType;
use App\Models\Building\Master\AssetMasterBuildingClass;
use App\Models\Building\Master\AssetMasterBuildingBeamType;
use App\Models\Building\Master\AssetMasterBuildingCategory;
use App\Models\Building\Master\AssetMasterBuildingSlabType;
use App\Models\Building\Master\AssetMasterBuildingWallType;
use App\Models\Building\Master\AssetMasterBuildingCondition;
use App\Models\Building\Master\AssetMasterBuildingColumnType;
use App\Models\Building\Master\AssetMasterBuildingFloorType;
use App\Models\Building\Master\AssetMasterBuildingUsePurpose;
use App\Models\Building\Master\AssetMasterBuildingStaircaseType;
use App\Models\Building\Master\AssetMasterBuildingFoundationType;
use App\Models\Building\Master\AssetMasterBuildingOccupantGrade;
use App\Models\Building\Master\AssetMasterBuildingSchemes;
use App\Models\Building\Master\AssetMasterBuildingSecurityFenchingType;

class BuildingController extends Controller
{
    public $buildingClasses;
    public $boundaryTypes;
    public $buildingConditions;
    public $buildingFoundationTypes;
    public $buildingTypes;
    public $buildingUsePurposes;
    public $buildingWallTypes;
    public $buildingBeamTypes;
    public $buildingColumnTypes;
    public $buildingSlabTypes;
    public $buildingStaircaseTypes;
    public $buildingCategories;
    public $departmentDetails;
    public $securityFenchingTypes;
    public $accessTypes;

    public function __construct()
    {
        $this->buildingClasses = AssetMasterBuildingClass::all();
        $this->boundaryTypes = AssetMasterBoundaryType::all();
        $this->buildingConditions = AssetMasterBuildingCondition::all();
        $this->buildingFoundationTypes = AssetMasterBuildingFoundationType::all();
        $this->buildingTypes = AssetMasterBuildingType::all();
        $this->buildingUsePurposes = AssetMasterBuildingUsePurpose::all();
        $this->buildingWallTypes = AssetMasterBuildingWallType::all();
        $this->buildingBeamTypes = AssetMasterBuildingBeamType::all();
        $this->buildingColumnTypes = AssetMasterBuildingColumnType::all();
        $this->buildingSlabTypes = AssetMasterBuildingSlabType::all();
        $this->buildingStaircaseTypes = AssetMasterBuildingStaircaseType::all();
        $this->buildingCategories = AssetMasterBuildingCategory::all();
        $this->departmentDetails = AssetMasterDeptOfState::all();
        $this->securityFenchingTypes = AssetMasterBuildingSecurityFenchingType::all();
        $this->accessTypes = AssetMasterBuildingAccessType::all();
        $this->middleware("auth");
        DB::enableQueryLog();
        Log::info("Building Controller Initialized.");
    }

    public function updateBuilding(Request $request)
    {
        try {
            if ((isset($request->id)) and (isset($request->_token))) {
                $buildingId = $request->id;
                $createdBy = Auth::user()->id;
                $currentTime = now();

                $validator = Validator::make($request->all(), [
                    'house_regn_no' => 'required',
                    'building_type_cd' => 'required',
                    'building_pupose_cd' => 'required',
                    'construction_year' => 'required',
                    'alloted_from_year' => 'required',
                    'alloted_from_month' => 'required',
                    'area_covered' => 'required',
                    'land_regn_detail' => 'required',
                    'foundation_type' => 'required',
                    'no_of_storey' => 'required',
                    'wall_type' => 'required',
                    'beam_type' => 'required',
                    'column_type' => 'required',
                    'slab_type' => 'required',
                    'staircase_type' => 'required',
                    'boundary_wall_type' => 'required',
                    'date_of_last_renovation' => 'required',
                    'lift_facility' => 'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'validation Failed'
                    ]);
                }

                $buildingData = AssetBuildingDetailsDraft::find($request->id);
                if ($buildingData) {
                    $storeHistStatus = DB::table('buildings.asset_building_details_draft_hist')->insertUsing([
                        'building_system_cd',
                        'house_regn_no',
                        'building_type_cd',
                        'building_pupose_cd',
                        'construction_year',
                        'alloted_from_year',
                        'alloted_from_month',
                        'area_covered',
                        'land_regn_detail',
                        'foundation_type',
                        'no_of_storey',
                        'wall_type',
                        'beam_type',
                        'column_type',
                        'slab_type',
                        'staircase_type',
                        'boundary_wall_type',
                        'date_of_last_renovation',
                        'lift_facility',
                        'any_other_defects_on_structure',
                        'created_at_office_cd',
                        'created_at',
                        'updated_at',
                        'created_by'
                    ], function ($query) use ($buildingId, $currentTime, $createdBy) {
                        $query->from('buildings.asset_building_details_draft')
                            ->where('building_system_cd', '=', $buildingId)
                            ->select(
                                'building_system_cd',
                                'house_regn_no',
                                'building_type_cd',
                                'building_pupose_cd',
                                'construction_year',
                                'alloted_from_year',
                                'alloted_from_month',
                                'area_covered',
                                'land_regn_detail',
                                'foundation_type',
                                'no_of_storey',
                                'wall_type',
                                'beam_type',
                                'column_type',
                                'slab_type',
                                'staircase_type',
                                'boundary_wall_type',
                                'date_of_last_renovation',
                                'lift_facility',
                                'any_other_defects_on_structure',
                                'created_at_office_cd',
                                DB::raw("'$currentTime' as created_at"),
                                DB::raw("'$currentTime' as updated_at"),
                                DB::raw("'$createdBy' as created_by")
                            );
                    });
                    // $storeHistStatus = True;
                    if ($storeHistStatus) {

                        $buildingData->house_regn_no = $request->house_regn_no;
                        $buildingData->building_type_cd = $request->building_type_cd;
                        $buildingData->building_pupose_cd = $request->building_pupose_cd;
                        $buildingData->construction_year = $request->construction_year;
                        $buildingData->alloted_from_year = $request->alloted_from_year;
                        $buildingData->alloted_from_month = $request->alloted_from_month;
                        $buildingData->area_covered = $request->area_covered;
                        $buildingData->land_regn_detail = $request->land_regn_detail;
                        $buildingData->foundation_type = $request->foundation_type;
                        $buildingData->no_of_storey = $request->no_of_storey;
                        $buildingData->wall_type = $request->wall_type;
                        $buildingData->beam_type = $request->beam_type;
                        $buildingData->column_type = $request->column_type;
                        $buildingData->slab_type = $request->slab_type;
                        $buildingData->staircase_type = $request->staircase_type;
                        $buildingData->boundary_wall_type = $request->boundary_wall_type;
                        $buildingData->date_of_last_renovation = $request->date_of_last_renovation;
                        $buildingData->lift_facility = $request->lift_facility;
                        $buildingData->any_other_defects_on_structure = $request->any_other_defects_on_structure;
                        $status = $buildingData->save();
                        if ($status) {
                            return response()->json([
                                'status' => 'success',
                                'message' => 'Data Updated successfully'
                            ]);
                        } else {
                            return response()->json([
                                'status' => 'failed',
                                'message' => 'Error to update the new data'
                            ]);
                        }
                    } else {
                        return response()->json([
                            'status' => 'failed',
                            'message' => 'Failed to copy the record in history table'
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Building ID not found'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal Server Error'
            ]);
        }
    }

	public function editDraftBuildingData(Request $request)
    {
        try {
            if ((isset($request->bld_sys_cd)) and (isset($request->_token))) {
                $buildingId = $request->bld_sys_cd;
                $createdBy = Auth::user()->id;
                $currentTime = now();

                // $validator = Validator::make($request->all(), [

                //     'bld_type_cd' => 'required',
                //     'is_mntd_by_npwd' => 'required',
                //     'bld_class_cd' => 'required',
                //     'bld_sys_cd' => 'required',
                //     'building_name_or_qtr_no' => 'required',
                //     'bld_owning_dept' => 'required',
                //     'bld_asset_geo_location_lat' => 'required',
                //     'bld_asset_geo_location_lon' => 'required'
                // ]);

                // if ($validator->fails()) {
                //     return response()->json([
                //         'status' => 'failed',
                //         'message' => 'validation Failed'
                //     ]);
                // }

                $buildingData = AssetBuildingDetailsDraft::find($request->bld_sys_cd);
                if ($buildingData) {
                    $storeHistStatus = DB::table('buildings.asset_building_details_draft_hist')->insertUsing([
                        'building_system_cd',
                        'building_type_cd',
                        'construction_year',
                        'sent_for_finalize',
                        'sent_for_finalize_on',
                        'sent_for_finalize_by',
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
                        'is_rejected',
                        'rejected_by',
                        'reason_of_rejection',
                        'date_of_rejection',
                        'division_cd',
                        'sub_division_cd',
                        'created_at_office_cd',
                        'created_at',
                        'updated_at',
                        'created_by'
                    ], function ($query) use ($buildingId, $currentTime, $createdBy) {
                        $query->from('buildings.asset_building_details_draft')
                            ->where('building_system_cd', '=', $buildingId)
                            ->select(
                                'building_system_cd',
                                'building_type_cd',
                                'construction_year',
                                'sent_for_finalize',
                                'sent_for_finalize_on',
                                'sent_for_finalize_by',
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
                                'is_rejected',
                                'rejected_by',
                                'reason_of_rejection',
                                'date_of_rejection',
                                'division_cd',
                                'sub_division_cd',
                                'created_at_office_cd',
                                'created_at',
                                'updated_at',
                                'created_by'
                            );
                    });

                    // $storeHistStatus = True;
                    if ($storeHistStatus) {

                        $buildingData->building_system_cd = $buildingId;
                        $buildingData->building_type_cd = $request->bld_type_cd;
                        $buildingData->is_maintained_by_npwd = $request->is_mntd_by_npwd;
                        $buildingData->building_class_cd = $request->bld_class_cd;
                        $buildingData->asset_owning_dept_cd = $request->bld_owning_dept;
                        $buildingData->lat = $request->bld_asset_geo_location_lat;
                        $buildingData->lon = $request->bld_asset_geo_location_lng;
                        if ($request->bld_class_cd == "0") {
                            $buildingData->qtr_no = $request->building_name_or_qtr_no;
                            $buildingData->bld_qtr_name = null;
                        } else {
                            $buildingData->bld_qtr_name = $request->building_name_or_qtr_no;
                            $buildingData->qtr_no = null;
                        }


                        $status = $buildingData->save();
                        if ($status) {
                            LOG::info('Data Updated successfully');
                            return response()->json([
                                'status' => 'success',
                                'message' => 'Data Updated successfully'
                            ]);
                        } else {
                            LOG::info('Error to update the new data');
                            return response()->json([
                                'status' => 'failed',
                                'message' => 'Error to update the new data'
                            ]);
                        }
                    } else {
                        LOG::info('Failed to copy the record in history table');
                        return response()->json([
                            'status' => 'failed',
                            'message' => 'Failed to copy the record in history table'
                        ]);
                    }
                } else {
                    LOG::info("Building ID not found");
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Building ID not found'
                    ]);
                }
            } else {
                LOG::info("Something went wrong");
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal Server Error'
            ]);
        }
    }

    public function freezeBuilding(Request $request)
    {
        try {
            if (isset($request->_token)) {
                $createdBy = Auth::user()->id;
                $currentTime = now();

                // Copy data from student_table_draft to another_table
                DB::table('buildings.asset_building_details')->insertUsing([
                    'building_system_cd',
                    'building_type_cd',
                    'construction_year',
                    'created_at_office_cd',
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
                    'created_at',
                    'updated_at',
                    'created_by'
                ], function ($query) use ($currentTime, $createdBy) {
                    $query->from('buildings.asset_building_details_draft')
                        ->where('sent_for_finalize', '=', 'Y')
                        ->where('created_at_office_cd', '=', Auth::user()->office)
                        ->select(
                            'building_system_cd',
                            'building_type_cd',
                            'construction_year',
                            'created_at_office_cd',
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
                            DB::raw("'$currentTime' as created_at"),
                            DB::raw("'$currentTime' as updated_at"),
                            DB::raw("'$createdBy' as created_by")
                        );
                });

                DB::table('buildings.asset_building_details_draft')
                    ->where('sent_for_finalize', '=', 'Y')
                    ->where('created_at_office_cd', '=', Auth::user()->office)
                    ->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Freeze all data successfully'
                ]);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Unothorized Access'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => $e
            ]);
        }
    }

    public function getBuildingType(Request $request)
    {
        $buildingTypes = AssetMasterBuildingType::where('building_class_cd', $request->building_class_cd)->get();
        return $buildingTypes;
    }

    public function getBuildingLocation(Request $request)
    {
        Log::info("Building class: " . $request->building_class_cd);
        Log::info("Division: " . session('userMapping')->division_cd);
        Log::info("Division CD: " . $request->division_cd);
        Log::info("Sub Division CD: " . $request->sub_division_cd);

        if ($request->division_cd == null && $request->sub_division_cd == null) {
            $buildingLocations = DB::table('buildings.asset_master_building_locations')
                ->select('location_cd', 'location_name')
                ->where('building_class_cd', '=', $request->building_class_cd)
                ->where('division_cd', '=', session('userMapping')->division_cd)
                ->get();
        } else {
            $buildingLocations = DB::table('buildings.asset_master_building_locations')
                ->select('location_cd', 'location_name')
                ->where('building_class_cd', '=', $request->building_class_cd)
                ->where('division_cd', '=', $request->division_cd)
                ->where('sub_division_cd', '=', $request->sub_division_cd)
                ->get();
        }
        Log::info("Buliding location list: " . json_encode($buildingLocations, JSON_PRETTY_PRINT));
        if ($buildingLocations->count() == 0) {
            return 'null';
        } else {
            return $buildingLocations;
        }
    }

    public function getGeneralAbstract()
    {
        try {
            DB::enableQueryLog();
            Log::info('Building controller: ');
            $generalAbstractDetails = DB::table('buildings.asset_building_details as bld')
                ->leftJoin('asset_master_divisions as div', 'bld.division_cd', '=', 'div.division_cd')
                ->select(
                    'bld.division_cd',
                    DB::raw("COUNT(CASE WHEN bld.building_class_cd = '0' THEN 1 END) AS residential_building_number"),
                    DB::raw("COUNT(CASE WHEN bld.building_class_cd = '1' THEN 1 END) AS non_residential_building_number"),
                    DB::raw("SUM(CASE WHEN bld.building_class_cd = '0' THEN bld.plinth_area ELSE 0 END) AS residential_plinth_area"),
                    DB::raw("SUM(CASE WHEN bld.building_class_cd = '1' THEN bld.plinth_area ELSE 0 END) AS non_residential_plinth_area"),
                    DB::raw('COUNT(bld.*) AS total_building_number'),
                    DB::raw('SUM(bld.plinth_area) AS total_plinth_area'),
                    'div.division_name'
                )
                ->groupBy('bld.division_cd', 'div.division_name')
                ->get();
            $totalGeneralAbstractDetail = DB::table('buildings.asset_building_details')
                ->select(
                    DB::raw("COUNT(CASE WHEN building_class_cd = '0' THEN 1 END) as total_residential_building_number"),
                    DB::raw("COUNT(CASE WHEN building_class_cd = '1' THEN 1 END) as total_non_residential_building_number"),
                    DB::raw("SUM(CASE WHEN building_class_cd = '0' THEN plinth_area ELSE 0 END) as total_residential_plinth_area"),
                    DB::raw("SUM(CASE WHEN building_class_cd = '1' THEN plinth_area ELSE 0 END) as total_non_residential_plinth_area"),
                    DB::raw("COUNT(*) as total_building_number"),
                    DB::raw("SUM(plinth_area) as total_plinth_area")
                )
                ->first();

            $totalResidentialDetails = DB::table('buildings.asset_building_details as bld')
                ->leftJoin('buildings.asset_master_building_types as bldType', 'bld.building_type_cd', '=', 'bldType.building_type_cd')
                ->select(
                    DB::raw("COUNT(CASE WHEN bld.bld_catg = '0' THEN 1 END) AS rcc"),
                    DB::raw("COUNT(CASE WHEN bld.bld_catg = '1' THEN 1 END) AS hill_type"),
                    DB::raw("COUNT(CASE WHEN bld.bld_catg = '2' THEN 1 END) AS semi_pucca"),
                    DB::raw("SUM(CASE WHEN bld.building_class_cd = '0' THEN bld.plinth_area ELSE 0 END) AS residential_plinth_area"),
                    DB::raw("SUM(CASE WHEN bld.building_class_cd = '1' THEN bld.plinth_area ELSE 0 END) AS non_residential_plinth_area"),
                    DB::raw("COUNT(bld.*) AS total_building_number"),
                    DB::raw("SUM(bld.plinth_area) AS total_plinth_area"),
                )
                ->where('bld.building_class_cd', '=', '0')
                ->first();

            $residentialDetails = DB::table('buildings.asset_building_details as bld')
                ->leftJoin('buildings.asset_master_building_types as bldType', 'bld.building_type_cd', '=', 'bldType.building_type_cd')
                ->select(
                    'bld.building_type_cd',
                    DB::raw("COUNT(CASE WHEN bld.bld_catg = '0' THEN 1 END) AS rcc"),
                    DB::raw("COUNT(CASE WHEN bld.bld_catg = '1' THEN 1 END) AS hill_type"),
                    DB::raw("COUNT(CASE WHEN bld.bld_catg = '2' THEN 1 END) AS semi_pucca"),
                    DB::raw("SUM(CASE WHEN bld.building_class_cd = '0' THEN bld.plinth_area ELSE 0 END) AS residential_plinth_area"),
                    DB::raw("SUM(CASE WHEN bld.building_class_cd = '1' THEN bld.plinth_area ELSE 0 END) AS non_residential_plinth_area"),
                    DB::raw("COUNT(bld.*) AS total_building_number"),
                    DB::raw("SUM(bld.plinth_area) AS total_plinth_area"),
                    'bldType.building_type_descr'
                )
                ->where('bld.building_class_cd', '=', '0')
                ->groupBy('bld.building_type_cd', 'bldType.building_type_descr')
                ->get();

            $nonResidentialDetails = DB::table('buildings.asset_building_details as bld')
                ->leftJoin('buildings.asset_master_building_types as bldType', 'bld.building_type_cd', '=', 'bldType.building_type_cd')
                ->select(
                    'bld.building_type_cd',
                    DB::raw("COUNT(CASE WHEN bld.bld_catg = '0' THEN 1 END) AS rcc"),
                    DB::raw("COUNT(CASE WHEN bld.bld_catg = '1' THEN 1 END) AS hill_type"),
                    DB::raw("COUNT(CASE WHEN bld.bld_catg = '2' THEN 1 END) AS semi_pucca"),
                    DB::raw("SUM(CASE WHEN bld.building_class_cd = '0' THEN bld.plinth_area ELSE 0 END) AS residential_plinth_area"),
                    DB::raw("SUM(CASE WHEN bld.building_class_cd = '1' THEN bld.plinth_area ELSE 0 END) AS non_residential_plinth_area"),
                    DB::raw("COUNT(bld.*) AS total_building_number"),
                    DB::raw("SUM(bld.plinth_area) AS total_plinth_area"),
                    'bldType.building_type_descr'
                )
                ->where('bld.building_class_cd', '=', '1')
                ->groupBy('bld.building_type_cd', 'bldType.building_type_descr')
                ->get();

            $totalNonResidentialDetails = DB::table('buildings.asset_building_details as bld')
                ->leftJoin('buildings.asset_master_building_types as bldType', 'bld.building_type_cd', '=', 'bldType.building_type_cd')
                ->select(
                    DB::raw("COUNT(CASE WHEN bld.bld_catg = '0' THEN 1 END) AS rcc"),
                    DB::raw("COUNT(CASE WHEN bld.bld_catg = '1' THEN 1 END) AS hill_type"),
                    DB::raw("COUNT(CASE WHEN bld.bld_catg = '2' THEN 1 END) AS semi_pucca"),
                    DB::raw("SUM(CASE WHEN bld.building_class_cd = '0' THEN bld.plinth_area ELSE 0 END) AS residential_plinth_area"),
                    DB::raw("SUM(CASE WHEN bld.building_class_cd = '1' THEN bld.plinth_area ELSE 0 END) AS non_residential_plinth_area"),
                    DB::raw("COUNT(bld.*) AS total_building_number"),
                    DB::raw("SUM(bld.plinth_area) AS total_plinth_area"),
                )
                ->where('bld.building_class_cd', '=', '1')
                ->first();
            $query = DB::getQueryLog();
            Log::info($query);
            return view('building.abstract', compact('generalAbstractDetails', 'residentialDetails', 'nonResidentialDetails', 'totalGeneralAbstractDetail', 'totalResidentialDetails', 'totalNonResidentialDetails'));
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function search(Request $request)
    {
        try {
            if ((isset($request->id)) and ($request->header('X-CSRF-TOKEN'))) {
                $buildingId = $request->id;
                $housindDetails = DB::table('buildings.asset_building_details as building')
                    ->select(
                        'building.building_system_cd',
                        'building.building_type_cd',
                        'building.construction_year',
                        'building.created_by',
                        'building.created_at_office_cd',
                        'building.asset_name',
                        'building.building_class_cd',
                        'building.bld_qtr_name',
                        'building.qtr_no',
                        'building.bld_catg',
                        'building.plinth_area',
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
                        'buildingDept.dept_name as dpt_name',
                        'owningBuildingDept.dept_name as owning_dpt_name',
                    )
                    ->leftJoin('buildings.asset_master_building_types as buildingType', 'building.building_type_cd', '=', 'buildingType.building_type_cd')
                    ->leftJoin('buildings.asset_master_building_class as buildingClass', 'building.building_class_cd', '=', 'buildingClass.building_class_cd')
                    ->leftJoin('buildings.asset_master_building_category as buildingCtg', 'building.bld_catg', '=', 'buildingCtg.building_catg_cd')
                    ->leftJoin('buildings.asset_master_building_locations as buildingLocation', 'building.building_location_cd', '=', 'buildingLocation.location_cd')
                    ->leftJoin('public.asset_master_dept_of_state as buildingDept', 'building.occupant_dept_cd', '=', 'buildingDept.id')
                    ->leftJoin('public.asset_master_dept_of_state as owningBuildingDept', 'building.asset_owning_dept_cd', '=', 'owningBuildingDept.id')
                    ->where('building_system_cd', $buildingId)
                    ->orderByDesc('building.updated_at')
                    ->get()->first();
                if ($housindDetails) {
                    return response()->json([
                        'status' => 'success',
                        'message' => $housindDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Building details not found!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal Server Error'
            ]);
        }
    }
    public function getHousingCoordinates(Request $request)
    {
        try {
            $bld_division = $request->input('bld_division');
            $bld_sub_division = $request->input('bld_sub_division');
            $bld_class = $request->input('bld_class');
            $bld_loc = $request->input('bld_loc');
            $bld_catg = $request->input('bld_catg');
            $bld_type = $request->input('bld_type');
            $abstractBaseQuery = DB::table('buildings.asset_building_details as bld')
                ->select(
                    'bld.building_type_cd',
                    'bld_type.building_type_descr',
                    DB::raw('COUNT(bld.building_system_cd) AS building_count'),
                    DB::raw('SUM(bld.plinth_area) AS plinth_area')
                )
                ->leftJoin('buildings.asset_master_building_types as bld_type', 'bld.building_type_cd', '=', 'bld_type.building_type_cd');
            $baseQuery = DB::table('buildings.asset_building_details')
                ->select('building_system_cd', 'lat', 'lon')
                ->whereNotNull('lat')
                ->whereNotNull('lon');
            if ($bld_division != 'A') {
                $abstractBaseQuery->where('bld.division_cd', $bld_division);
                $baseQuery->where('division_cd', $bld_division);
            }
            if ($bld_sub_division != 'A') {
                $baseQuery->where('sub_division_cd', $bld_sub_division);
            }
            if ($bld_class != 'A') {
                $baseQuery->where('building_class_cd', $bld_class);
            }
            if ($bld_loc != 'A') {
                $baseQuery->where('building_location_cd', $bld_loc);
            }
            if ($bld_catg != 'A') {
                $baseQuery->where('bld_catg', $bld_catg);
            }
            if ($bld_type != 'A') {
                $baseQuery->where('building_type_cd', $bld_type);
            }
            $bldAbsDetails = $abstractBaseQuery->groupBy('bld.building_type_cd', 'bld_type.building_type_descr')->get();
            $bldDetails = $baseQuery->orderBy('building_system_cd', 'asc')->get();
            if ($bldAbsDetails->count()) {
                if ($bldDetails->count()) {
                    $coordinatesList = [];
                    foreach ($bldDetails as $point) {
                        $coordinatesList[] = [
                            'id' => $point->building_system_cd,
                            'coords' => [
                                'lat' => $point->lat,
                                'lng' => $point->lon,
                            ],
                        ];
                    }
                    return response()->json([
                        'status' => 200,
                        'message' => $coordinatesList,
                        'abstract' => $bldAbsDetails
                    ]);
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Building coordinates not available!',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 204,
                    'message' => 'Building details not available!',
                    'result' => null
                ]);
            }
            $query = DB::getQueryLog();
            Log::info($query);
        } catch (Exception $e) {
            Log::error("Error in getting getHousingCoordinates: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }

    public function getHousingCoordinatesById(Request $request)
    {
        try {
            $coordinates = DB::table('buildings.asset_building_details_draft')
                ->select('building_system_cd', 'lat', 'lon', 'bld_qtr_name', 'qtr_no', 'division_cd', 'building_class_cd')
                ->where('building_system_cd', $request->id)
                ->get()->first();
            $lat = null;
            $lng = null;
            $query = DB::getQueryLog();
            Log::info($query);
            if ($coordinates) {
                $lat = $coordinates->lat;
                $lng = $coordinates->lon;
                $div_cd = $coordinates->division_cd;
                LOG::info("found cordinate");
                $all_cordinates_from_approved_data = DB::table('buildings.asset_building_details')
                    ->select('building_system_cd', 'lat', 'lon', 'bld_qtr_name', 'qtr_no', 'building_class_cd')
                    ->where('division_cd', $div_cd)
                    ->get()->toArray();

                // ->where('lon', $lng)
                // ->whereNotNull('lat')
                // ->whereNotNull('lon')




                return response()->json([
                    'status' => 200,
                    'message' => 'Building coordinates fetched successfully!',
                    'result' => $coordinates,
                    'all_approved_cordinated' => $all_cordinates_from_approved_data
                ]);
            } else {
                return response()->json([
                    'status' => 204,
                    'message' => 'Building details not available!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("Error in getting getHousingCoordinatesById: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }

    public function createAdditionalData(Request $request)
    {
        try {
            DB::enableQueryLog();
            Log::info('Additing additional data for existing building');
            Log::info('Building ID: ' . $request->input('id'));
            $user = Auth::user();
            $buildingID = $request->input('id');
            $buildingClass = $request->input('class');
            $buildingDetails = DB::table('buildings.asset_building_details as building')
                ->select(
                    'building.building_system_cd',
                    'building.building_type_cd',
                    'building.construction_year',
                    'building.created_at_office_cd',
                    'building.asset_name',
                    'building.is_maintained_by_npwd',
                    'building.building_class_cd',
                    'building.bld_qtr_name',
                    'building.qtr_no',
                    'building.bld_catg',
                    'building.occupant_dept_cd',
                    'building.asset_owning_dept_cd',
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
                ->where('created_at_office_cd', '=', session('office_cd'))
                ->where('building_system_cd', $request->input("id"))
                ->get();

            $boundaryTypes = $this->boundaryTypes;
            $buildingConditions = $this->buildingConditions;
            $buildingFoundationTypes = $this->buildingFoundationTypes;
            $buildingTypes = $this->buildingTypes;
            $buildingUsePurposes = $this->buildingUsePurposes;
            $buildingWallTypes = $this->buildingWallTypes;
            $buildingBeamTypes = $this->buildingBeamTypes;
            $buildingColumnTypes = $this->buildingColumnTypes;
            $buildingSlabTypes = $this->buildingSlabTypes;
            $buildingStaircaseTypes = $this->buildingStaircaseTypes;
            $buildingCategories = $this->buildingCategories;
            $departmentDetails = $this->departmentDetails;
            $securityFenchingTypes = $this->securityFenchingTypes;
            $accessTypes = $this->accessTypes;
            $buildingClasses = $this->buildingClasses;
            $builingFloorTypes = AssetMasterBuildingFloorType::all();
            $builingSchemes = AssetMasterBuildingSchemes::all();
            $occupantGrades = AssetMasterBuildingOccupantGrade::all();
            $query = DB::getQueryLog();
            Log::info($query);
            return view('building.createAdditionalDetails', compact(
                'user',
                'buildingID',
                'buildingClass',
                'boundaryTypes',
                'buildingConditions',
                'buildingFoundationTypes',
                'buildingTypes',
                'buildingUsePurposes',
                'buildingDetails',
                'buildingWallTypes',
                'buildingBeamTypes',
                'buildingColumnTypes',
                'buildingSlabTypes',
                'buildingStaircaseTypes',
                'buildingClasses',
                'buildingCategories',
                'departmentDetails',
                'securityFenchingTypes',
                'accessTypes',
                'builingFloorTypes',
                'builingSchemes',
                'occupantGrades'
            ));
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function storeAdditionalData(Request $request)
    {
        try {
            Log::info('Adding addtional data to ' . $request->bld_sys_cd);
            if ((isset($request->bld_sys_cd)) and (isset($request->_token))) {
                $buildingData = AssetBuildingDetail::find($request->bld_sys_cd);
                if ($buildingData) {
                    $buildingData->bld_catg = $request->bld_catg;
                    $buildingData->floor_type_cd = $request->floor_type_cd;
                    $buildingData->has_emergency_exit = $request->has_emergency_exit;
                    $buildingData->has_staircase = $request->has_staircase;
                    $buildingData->has_lift = $request->has_lift;
                    $buildingData->has_ramp = $request->has_ramp;
                    $buildingData->plinth_area = $request->plinth_area;
                    $buildingData->plot_area = $request->plot_area;
                    $buildingData->building_access_type_cd = $request->buildingAccess;
                    $buildingData->construction_year = $request->construction_year;
                    $buildingData->construction_cost = $request->construction_cost;
                    $buildingData->scheme_cd = $request->scheme_cd;
                    $buildingData->is_partial_data = "N";
                    $buildingData->has_water_supply = $request->has_water_supply;
                    $buildingData->has_electricity = $request->has_electricity;
                    $buildingData->has_sanitary = $request->has_sanitary;
                    $buildingData->is_pwd_friendly = $request->is_pwd_friendly;
                    $buildingData->is_fire_safety_available = $request->is_fire_safety_available;
                    $buildingData->security_fenching_type_cd = $request->fencing_type;
                    $buildingData->last_repaired_cost = $request->last_repaired_cost;
                    $buildingData->last_repaired_scheme_cd = $request->last_repaired_scheme_cd;
                    $buildingData->year_of_last_repaired = $request->year_of_last_repaired;
                    $buildingData->occupied_from = $request->occupied_from;
                    $buildingData->occupied_to = $request->occupied_to;
                    $buildingData->occupant_grade_cd = $request->occupant_grade_cd;
                    $buildingData->occupant_name = $request->occupant_name;
                    $buildingData->occupant_dept_cd = $request->occupant_dept_cd;
                    $buildingData->remark = $request->remark;
                    $status = $buildingData->save();
                    if ($status) {
                        LOG::info('Additional data added successfully');
                        return redirect()->route('manage.housing.index')
                            ->with('success', 'Additional Building details added successfully for Building ID :  ' . $request->bld_sys_cd);
                    } else {
                        LOG::info('Error to update the new data');
                        return redirect()->route('manage.housing.index')
                            ->with('failed', 'Failed to add additional building details');
                    }
                } else {
                    LOG::info("Building ID not found");
                    return redirect()->route('manage.housing.index')
                        ->with('failed', 'Failed to add additional building details');
                }
            } else {
                LOG::info("Something went wrong");
                return redirect()->route('manage.housing.index')
                    ->with('failed', 'Failed to add additional building details');
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->route('manage.housing.index')
                ->with('failed', 'Failed to add additional building details');
        }
    }
}
