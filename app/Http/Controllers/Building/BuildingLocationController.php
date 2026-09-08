<?php

namespace App\Http\Controllers\Building;

use App\Http\Controllers\Controller;
use App\Models\AssetMasterDivision;
use App\Models\AssetMasterOfficeType;
use App\Models\Building\Master\AssetMasterBuildingClass;
use App\Models\Building\Master\AssetMasterBuildingLocation;
use App\Models\DepartmentDetail;
use App\Models\OfficeDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BuildingLocationController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        DB::enableQueryLog();
        Log::info('Building location controller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isSuperAdmin = Auth::user()->user_role_id == 1;
        // Base query
        $officeQuery = DB::table('office_details')
            ->leftJoin('department_details', 'office_details.department_id', '=', 'department_details.id')
            ->select('office_details.*', 'department_details.department_name');

        $deptQuery = DepartmentDetail::query();

        if (!$isSuperAdmin) {
            $officeQuery->where('office_details.department_id', Auth::user()->department);
            $deptQuery->where('id', session('user_dept_cd'))->orderBy('created_at', 'DESC');
        }
        // get offices and departments
        $office_details = $officeQuery->get();
        $dept_details   = $deptQuery->get();

        // Master data
        $officeTypes = AssetMasterOfficeType::all();
        $divisions = AssetMasterDivision::where('dept_cd', '6')->get();
        $buildingClasses = AssetMasterBuildingClass::all();

        $parent_office = OfficeDetail::select('id', 'office_name')
            ->where('parent_office', 'Y')
            ->orderBy('id', 'asc')
            ->get();

        $locationDetails = DB::table('buildings.asset_master_building_locations as location')
            ->select('location.*', 'division.division_name', 'subDivision.sub_div_name', 'buildingClass.building_class_descr')
            ->leftJoin('public.asset_master_divisions as division', 'location.division_cd', '=', 'division.division_cd')
            ->leftJoin('public.asset_master_sub_divisions as subDivision', 'location.sub_division_cd', '=', 'subDivision.sub_div_cd')
            ->leftJoin('buildings.asset_master_building_class as buildingClass', 'location.building_class_cd', '=', 'buildingClass.building_class_cd')
            ->orderBy('location.updated_at', 'desc')
            ->get();
        $query = DB::getQueryLog();
        Log::info($query);

        return view('building.location.index', compact(
            'office_details',
            'dept_details',
            'parent_office',
            'officeTypes',
            'divisions',
            'buildingClasses',
            'locationDetails'
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
        $fields = $request->validate([
            'location_name'  => 'required|string',
            'division_cd'  => 'required',
            'sub_division_cd'  => 'required',
            'building_class_cd'  => 'required',
        ]);

        $currentDate = Carbon::now()->format('Ymd');
        $twoDigitNumber = mt_rand(1, 99);
        $randomCode = $currentDate . $twoDigitNumber;
        $data = [
            'location_cd' => $randomCode,
            'location_name' => $request->location_name,
            'division_cd' => $request->division_cd,
            'sub_division_cd' => $request->sub_division_cd,
            'building_class_cd' => $request->building_class_cd,
        ];

        $status = AssetMasterBuildingLocation::create($data);

        if ($status) {
            return redirect()->back()
                ->with('success', 'Building location added successfully!');
        } else {
            return redirect()->back()
                ->with('error', 'Failed to add new location!');
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
     * @param  string  $location_cd
     * @return \Illuminate\Http\Response
     */
    public function edit(string $location_cd)
    {
        $location = DB::table('buildings.asset_master_building_locations')
            ->where('location_cd', $location_cd)
            ->first();

        if (!$location) {
            return redirect()->route('building-location.index')
                ->with('error', 'Location not found.');
        }

        $divisions     = AssetMasterDivision::where('dept_cd', '6')->get();
        $buildingClasses = AssetMasterBuildingClass::all();

        return view('building.location.edit', compact('location', 'divisions', 'buildingClasses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $location_cd
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $location_cd)
    {
        $validated = $request->validate([
            'location_name'    => 'required|string|max:255',
            'building_class_cd' => 'required|string',
            'division_cd'      => 'required|string',
            'sub_division_cd'  => 'required|string',
        ]);

        $affected = DB::table('buildings.asset_master_building_locations')
            ->where('location_cd', $location_cd)
            ->update([
                ...$validated,
                'updated_at' => now(),
            ]);

        if (!$affected) {
            return back()->with('error', 'No record found or nothing changed.');
        }

        return redirect()->route('building-location.index')
            ->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $location_cd
     * @return \Illuminate\Http\Response
     */
    public function destroy($location_cd)
    {
        $affected = DB::table('buildings.asset_master_building_locations')
            ->where('location_cd', $location_cd)
            ->delete();

        if (!$affected) {
            return back()->with('error', 'Record not found.');
        }

        return redirect()->route('building-location.index')
            ->with('success', 'Location deleted successfully.');
    }
}
