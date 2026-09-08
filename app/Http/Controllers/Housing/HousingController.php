<?php

namespace App\Http\Controllers\Housing;

use App\Http\Controllers\Controller;
use App\Models\Building\AssetBuildingDetail;
use App\Models\Building\AssetBuildingDetailDraft;
use App\Models\Building\AssetBuildingDetailsDraft;
use App\Models\Building\Master\AssetMasterBoundaryType;
use App\Models\Building\Master\AssetMasterBuildingBeamType;
use App\Models\Building\Master\AssetMasterBuildingClass;
use App\Models\Building\Master\AssetMasterBuildingColumnType;
use App\Models\Building\Master\AssetMasterBuildingCondition;
use App\Models\Building\Master\AssetMasterBuildingFoundationType;
use App\Models\Building\Master\AssetMasterBuildingLocation;
use App\Models\Building\Master\AssetMasterBuildingSlabType;
use App\Models\Building\Master\AssetMasterBuildingStaircaseType;
use App\Models\Building\Master\AssetMasterBuildingType;
use App\Models\Building\Master\AssetMasterBuildingUsePurpose;
use App\Models\Building\Master\AssetMasterBuildingWallType;
use App\Models\Common\AssetMasterRoadSubAsset;
use App\Models\Road\Master\AssetMasterDeptOfState;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HousingController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        DB::enableQueryLog();
        Log::info("Housing controller!");
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
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
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

        $finalizedBuildingDetails = [];
        $finalizedHousingBaseQuery = DB::table('buildings.asset_building_details as building')
            ->select(
                'building.*',
                'buildingType.building_type_descr',
                'buildingClass.building_class_descr',
                'buildingCtg.building_catg_descr',
                'buildingLocation.location_name',
                'buildingAccess.access_type_descr',
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

        if ($users_office_type_cd == 'HQ' || $users_office_type_cd == 'ADM' || $users_office_type_cd == 'ECO' || $users_office_type_cd == 'DA' || $users_office_type_cd == 'SO') {
            $finalizedBuildingDetails = $finalizedHousingBaseQuery->get();
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
            $finalizedBuildingDetails = $finalizedHousingBaseQuery
                ->whereIn('created_at_office_cd', $ZOOffices)
                ->get();
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
            $finalizedBuildingDetails = $finalizedHousingBaseQuery
                ->whereIn('created_at_office_cd', $COOffices)
                ->get();
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
            $finalizedBuildingDetails = $finalizedHousingBaseQuery
                ->whereIn('created_at_office_cd', $DOOffices)
                ->get();
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
            $finalizedBuildingDetails = $finalizedHousingBaseQuery
                ->whereIn('created_at_office_cd', $SDOffices)
                ->get();
        }

        $buildingDetails = DB::table('buildings.asset_building_details_draft as building')
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
                'building.reason_of_rejection',
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
            ->where('sent_for_finalize', '=', 'N')
            ->orderBy('building.created_at', 'desc')
            ->get();

        $query = DB::getQueryLog();
        Log::info($query);

        return view('building.index', array_merge(
            $this->masterData(),
            compact('user', 'buildingDetails', 'finalizedBuildingDetails')
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $buildingDetails = AssetBuildingDetailDraft::with([
            'buildingType',
            'buildingClass',
            'buildingCategory',
            'owningDept'
        ])
            ->where('created_at_office_cd', '=', session('office_cd'))
            ->where('sent_for_finalize', '=', 'N')
            ->orderBy('created_at', 'desc')
            ->get();

        $query = DB::getQueryLog();
        Log::info($query);
        return view('building.manage_building.create', array_merge(
            $this->masterData(),
            compact('user', 'buildingDetails')
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info("Inside Save Data of Building Asset");
        $user = Auth::user();
        $office_cd = $user->office;
        $users_office_type_cd = $user->office_type_cd;
        $division_name = null;
        $zone_cd = null;
        $circle_cd = null;
        $division_cd = null;
        $sub_division_cd = null;
        $userMappingDetails = DB::table('asset_user_mappings')
            ->select('zone_cd', 'circle_cd', 'division_cd', 'sub_division_cd')
            ->where('user_id', '=', $user->id)
            ->get()->first();


        $zone_cd = $userMappingDetails->zone_cd;
        $circle_cd = $userMappingDetails->circle_cd;
        $division_cd = $userMappingDetails->division_cd;
        $sub_division_cd = $userMappingDetails->sub_division_cd;

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

        $validator = Validator::make($request->all(), [
            'asset_geo_location_lat' => 'required',
            'asset_geo_location_lng' => 'required',
            'building_class_cd' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('manage.housing.create')
                ->with('error', 'Bad Request. Validation Failed!');
        } else {
            $prefix = rand(10, 99);
            $postfix = rand(100, 999);

            $building_code = $prefix . $user->office . $request->construction_year . $postfix;
            $isDuplicateCode = AssetBuildingDetailsDraft::where('building_system_cd', $building_code)
                ->exists();
            if ($isDuplicateCode) {
                return redirect()->route('manage.housing.create')
                    ->with('error', 'Duplicate Entry Found!');
            }
            // get the district code
            $district = DB::table('asset_master_divisions')
                ->select('district_cd')
                ->where('division_cd', session('userMapping')->division_cd)
                ->get()->first();

            $dist_cd = null;
            $division = $division_cd;
            $sub_division = $sub_division_cd;
            if ($district)
                $dist_cd = $district->district_cd;
            // check if building_location_cd is available or not in the request
            if (isset($request['building_location_cd'])) {
                $buildingLocation = AssetMasterBuildingLocation::where('location_cd', $request['building_location_cd'])
                    ->get()->first();
                Log::info("Location: " . $buildingLocation);
                if ($buildingLocation) {
                    $division = $buildingLocation->division_cd;
                    $sub_division = $buildingLocation->sub_division_cd;
                }
            }

            // get the maker checker status for housing
            $makerCheckerStatus = AssetMasterRoadSubAsset::getMakerCheckerStatus('5');
            if ($makerCheckerStatus == 'Y') {
                $building = new AssetBuildingDetailDraft();
            } else {
                $building = new AssetBuildingDetail();
                $building->approved_by = $user->id;
                $building->approved_at = Carbon::now();
            }
            $building->building_system_cd = $building_code;
            $building->qtr_no = isset($request->quarter_no) ? Str::upper($request->quarter_no) : null;
            $building->bld_qtr_name = isset($request->building_name) ? Str::upper($request->building_name) : null;
            $building->is_maintained_by_npwd = $request->maintained_by;
            $building->building_class_cd = $request->building_class_cd;
            $building->building_location_cd = $request->building_location_cd;
            $building->building_type_cd = $request->building_type_cd;
            $building->asset_owning_dept_cd = $request->owning_dept;
            $building->lat = $request->asset_geo_location_lat;
            $building->lon = $request->asset_geo_location_lng;
            $building->created_at = Carbon::now();
            $building->updated_at = Carbon::now();
            $building->created_by = $user->id;
            $building->created_at_office_cd = $office_cd;
            $building->division_cd = $division;
            $building->sub_division_cd = $sub_division;
            $building->dist_cd = $dist_cd;
            $status = $building->save();
            if ($status) {
                Log::info("Building Asset Created Successfully With id: " . $building_code);
                return redirect()->route('manage.housing.create')
                    ->with('success', 'New Building details added successfully with ID :  ' . $building_code);
            } else {
                Log::info("Failed to Add Road details!!! Please Try After Some Time....");
                return redirect()->route('manage.housing.create')
                    ->with('failed', 'Failed to Add Road details!!! Please Try After Some Time....');
            }
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
        $building = AssetBuildingDetail::with([
            'buildingType',
            'buildingClass',
            'buildingCategory',
            'accessType',
            'securityFencingType',
            'division',
            'subDivision',
            'occupantDept',
            'owningDept',
            'createdAtOffice',
            'createdBy',
            'approvedBy',
        ])
            ->where('building_system_cd', $id)
            ->firstOrFail();

        return view('building.show', compact('building'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $building = AssetBuildingDetailDraft::where('building_system_cd', $id)
            ->where('created_at_office_cd', '=', session('office_cd'))
            ->firstOrFail();

        return view('building.manage_building.edit', array_merge(
            $this->masterData(),
            compact('building')
        ));
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
        $validator = Validator::make($request->all(), [
            'asset_geo_location_lat' => 'required',
            'asset_geo_location_lng' => 'required',
            'building_class_cd'      => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('manage.housing.edit', $id)
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Validation failed. Please check the fields.');
        }

        $building = AssetBuildingDetailDraft::where('building_system_cd', $id)
            ->where('created_at_office_cd', '=', session('office_cd'))
            ->firstOrFail();

        $building->qtr_no                = isset($request->quarter_no) ? Str::upper($request->quarter_no) : null;
        $building->bld_qtr_name          = isset($request->building_name) ? Str::upper($request->building_name) : null;
        $building->is_maintained_by_npwd = $request->maintained_by;
        $building->building_class_cd     = $request->building_class_cd;
        $building->building_location_cd  = $request->building_location_cd;
        $building->building_type_cd      = $request->building_type_cd;
        $building->asset_owning_dept_cd  = $request->owning_dept;
        $building->lat                   = $request->asset_geo_location_lat;
        $building->lon                   = $request->asset_geo_location_lng;
        $building->updated_at            = Carbon::now();

        $status = $building->save();

        if ($status) {
            Log::info("Building Asset Updated Successfully: " . $id);
            return redirect()->route('manage.housing.create')
                ->with('success', 'Building details updated successfully.');
        }

        Log::info("Failed to update Building details for: " . $id);
        return redirect()->route('manage.housing.edit', $id)
            ->with('failed', 'Failed to update Building details! Please try after some time.');
    }

    public function destroy(Request $request)
    {
        if ($request->_token && $request->building_id) {
            $draftBuilding = AssetBuildingDetailDraft::findOrFail($request->building_id);
            $status = $draftBuilding->delete();
            if ($status) {
                return redirect()->back()->with('success', 'Building data deleted seccessfully');
            } else {
                return redirect()->back()->with('failed', 'Failed to delete the building data!');
            }
        }
    }
}
