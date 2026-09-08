<?php

namespace App\Http\Controllers\Housing;

use App\Http\Controllers\Controller;
use App\Models\Building\AssetBuildingDetail;
use App\Models\Building\Master\AssetMasterBoundaryType;
use App\Models\Building\Master\AssetMasterBuildingAccessType;
use App\Models\Building\Master\AssetMasterBuildingBeamType;
use App\Models\Building\Master\AssetMasterBuildingCategory;
use App\Models\Building\Master\AssetMasterBuildingClass;
use App\Models\Building\Master\AssetMasterBuildingColumnType;
use App\Models\Building\Master\AssetMasterBuildingCondition;
use App\Models\Building\Master\AssetMasterBuildingFloorType;
use App\Models\Building\Master\AssetMasterBuildingFoundationType;
use App\Models\Building\Master\AssetMasterBuildingLocation;
use App\Models\Building\Master\AssetMasterBuildingOccupantGrade;
use App\Models\Building\Master\AssetMasterBuildingSchemes;
use App\Models\Building\Master\AssetMasterBuildingSecurityFenchingType;
use App\Models\Building\Master\AssetMasterBuildingSlabType;
use App\Models\Building\Master\AssetMasterBuildingStaircaseType;
use App\Models\Building\Master\AssetMasterBuildingType;
use App\Models\Building\Master\AssetMasterBuildingUsePurpose;
use App\Models\Building\Master\AssetMasterBuildingWallType;
use App\Models\Road\Master\AssetMasterDeptOfState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HousingAdditionalDataController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        DB::enableQueryLog();
        Log::info("Housing Additional Data controller!");
    }
    private function masterData(): array
    {
        return [
            'buildingClasses'          => AssetMasterBuildingClass::all(),
            'boundaryTypes'            => AssetMasterBoundaryType::all(),
            'buildingConditions'       => AssetMasterBuildingCondition::all(),
            'buildingFoundationTypes'  => AssetMasterBuildingFoundationType::all(),
            'buildingTypes'            => AssetMasterBuildingType::all(),
            'buildingUsePurposes'      => AssetMasterBuildingUsePurpose::all(),
            'buildingWallTypes'        => AssetMasterBuildingWallType::all(),
            'buildingBeamTypes'        => AssetMasterBuildingBeamType::all(),
            'buildingColumnTypes'      => AssetMasterBuildingColumnType::all(),
            'buildingSlabTypes'        => AssetMasterBuildingSlabType::all(),
            'buildingStaircaseTypes'   => AssetMasterBuildingStaircaseType::all(),
            'departmentDetails'        => AssetMasterDeptOfState::all(),
            'builingFloorTypes'        => AssetMasterBuildingFloorType::all(),
            'builingSchemes'           => AssetMasterBuildingSchemes::all(),
            'occupantGrades'           => AssetMasterBuildingOccupantGrade::all(),
            'buildingCategories'       => AssetMasterBuildingCategory::all(),
            'accessTypes'              => AssetMasterBuildingAccessType::all(),
            'securityFenchingTypes'    => AssetMasterBuildingSecurityFenchingType::all()
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $buildingID)
    {
        $user = Auth::user();
        $buildingDetails = DB::table('buildings.asset_building_details as building')
            ->select(
                [
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
                    'buildingDept.dept_name as department_name',
                    'buildingOwningDept.dept_name as owning_dept_name'
                ]
            )
            ->leftJoin('buildings.asset_master_building_types as buildingType', 'building.building_type_cd', '=', 'buildingType.building_type_cd')
            ->leftJoin('buildings.asset_master_building_class as buildingClass', 'building.building_class_cd', '=', 'buildingClass.building_class_cd')
            ->leftJoin('public.asset_master_dept_of_state as buildingDept', 'building.occupant_dept_cd', '=', 'buildingDept.id')
            ->leftJoin('public.asset_master_dept_of_state as buildingOwningDept', 'building.asset_owning_dept_cd', '=', 'buildingOwningDept.id')
            ->where('building_system_cd', $buildingID)
            ->get()->first();
        return view('building.createAdditionalDetails', array_merge(
            $this->masterData(),
            compact('buildingID', 'buildingDetails')
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ((isset($request->bld_sys_cd)) and (isset($request->_token))) {
            $buildingData = AssetBuildingDetail::where('building_system_cd', $request->bld_sys_cd)->first();
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
                $buildingData->is_pwd_friendly = $request->is_pwd_friendly;
                $buildingData->is_fire_safety_available = $request->is_fire_safety_available;
                $buildingData->security_fenching_type_cd = $request->fencing_type;
                $buildingData->last_repaired_cost = $request->last_repaired_cost;
                $buildingData->last_repaired_scheme_cd = $request->last_repaired_scheme_cd;
                $buildingData->year_of_last_repaired = $request->year_of_last_repaired;
                $buildingData->total_no_of_units = $request->total_no_of_units;
                $buildingData->remark = $request->remark;

                $status = $buildingData->save();

                if ($status) {
                    log::info('Additional data added successfully');
                    return redirect()->route('manage.housing.index')
                        ->with('success', 'Additional Building details added successfully for Building ID :  ' . $request->bld_sys_cd);
                } else {
                    log::info('Error to update the new data');
                    return redirect()->route('manage.housing.index')
                        ->with('failed', 'Failed to add additional building details');
                }
            } else {
                LOG::info("Building ID not found");
                return redirect()->route('manage.housing.index')
                    ->with('failed', 'Failed to add additional building details');
            }
        } else {
            log::info("Something went wrong!");
            return redirect()->route('manage.housing.index')
                ->with('failed', 'Failed to add additional building details');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
