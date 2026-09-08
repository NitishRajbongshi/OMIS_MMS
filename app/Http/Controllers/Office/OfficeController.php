<?php

namespace App\Http\Controllers\Office;

use Exception;
use Carbon\Carbon;
use App\Helpers\MyHelper;
use App\Models\OfficeDetail;
use Illuminate\Http\Request;
use App\Models\AssetMasterZone;
use App\Models\DepartmentDetail;
use App\Models\AssetMasterCircle;
use Illuminate\Support\Facades\DB;
use App\Models\AssetMasterDivision;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AssetMasterOfficeType;
use App\Models\AssetMasterSubDivision;
use Illuminate\Support\Facades\Validator;
use App\Models\SubdistrictofspecificstateDetail;
use App\Models\AllblockstatewithcoveredvillageDetail;

class OfficeController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index()
    {
        try {
            DB::enableQueryLog();
            Log::info('Office controller: ');
            $parent_office = OfficeDetail::select('id', 'office_name')
                ->where('parent_office', 'Y')
                ->orderBy('id', 'asc')
                ->get();

            $dept_details = [];
            $office_details = [];
            if (Auth::user()->user_role_id == 1) { // super admin
                $dept_details = DepartmentDetail::all();
                $office_details = DB::table('office_details')
                    ->join('department_details', 'office_details.department_id', '=', 'department_details.id')
                    ->select('office_details.*', 'department_details.department_name')
                    ->orderBy('updated_at', 'desc')
                    ->get();
            } else { // departmental user
                $dept_details = DepartmentDetail::where('id', session('user_dept_cd'))
                    ->orderBy('created_at', 'DESC')->get();
                $office_details = DB::table('office_details')
                    ->join('department_details', 'office_details.department_id', '=', 'department_details.id')
                    ->select('office_details.*', 'department_details.department_name')
                    ->where('office_details.department_id', Auth::user()->department)
                    ->orderBy('updated_at', 'desc')
                    ->get();
            }
            $officeTypes = AssetMasterOfficeType::all();

            $query = DB::getQueryLog();
            Log::info($query);

            return view('office.index', compact(
                'office_details',
                'dept_details',
                'parent_office',
                'officeTypes'
            ));
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }

    public function store(Request $request)
    {
        DB::enableQueryLog();
        Log::info('Office controller: ');

        $fields = $request->validate([
            'office_name'  => 'required|string',
            'department_id'  => 'required|string',
            'parent_office'  => 'required|string',
            'office_type_cd'  => 'required|string'
        ]);

        if ($fields['parent_office'] == 'Y') {
            $fields['parent_office_id'] = $request->department_id;
        } else {
            $fields['parent_office_id'] = $request->parent_office_id;
        }

        if ($fields['office_type_cd'] == 'HQ') {
            $fields['office_level'] = '0';
            $fields['zone_cd'] = null;
            $fields['circle_cd'] = null;
            $fields['division_cd'] = null;
            $fields['sub_division_cd'] = null;
        }

        if ($fields['office_type_cd'] == 'ZO') {
            $fields['office_level'] = '1';
            $fields['zone_cd'] = $request->zone_cd;
            $fields['circle_cd'] = null;
            $fields['division_cd'] = null;
            $fields['sub_division_cd'] = null;
        }

        if ($fields['office_type_cd'] == 'CO') {
            $fields['office_level'] = '2';
            $fields['zone_cd'] = $request->zone_cd;
            $fields['circle_cd'] = $request->circle_cd;
            $fields['division_cd'] = null;
            $fields['sub_division_cd'] = null;
        }

        if ($fields['office_type_cd'] == 'DO') {
            $fields['office_level'] = '3';
            $fields['zone_cd'] = $request->zone_cd;
            $fields['circle_cd'] = $request->circle_cd;
            $fields['division_cd'] = $request->division_cd;
            $fields['sub_division_cd'] = null;
        }

        if ($fields['office_type_cd'] == 'SDO') {
            $fields['office_level'] = '4';
            $fields['zone_cd'] = $request->zone_cd;
            $fields['circle_cd'] = $request->circle_cd;
            $fields['division_cd'] = $request->division_cd;
            $fields['sub_division_cd'] = $request->sub_division_cd;
        }

        $status = OfficeDetail::create($fields);

        $query = DB::getQueryLog();
        Log::info($query);

        if ($status) {
            return redirect()->back()
                ->with('success', 'Office added successfully');
        } else {
            return redirect()->back()
                ->with('error', 'Something went wrong');
        }
    }

    public function update(Request $request)
    {
        try {
            if ((isset($request->id)) and (isset($request->_token))) {
                $office_id = $request->id;
                $deletedBy = Auth::user()->id;
                $deletedTime = now();
                $officeData = OfficeDetail::findOrFail($office_id);
                if ($officeData) {
                    // maintain history table
                    if (1) {
                        $officeData->office_name = $request->office_name;
                        $officeData->department_id = $request->department_id;
                        $officeData->updated_at = Carbon::now();
                        $status = $officeData->save();
                        if ($status) {
                            return response()->json([
                                'status' => 'success',
                                'message' => 'Office data updated successfully!'
                            ]);
                        } else {
                            LOG::info("Failed to update the office data!");
                            return response()->json([
                                'status' => 'failed',
                                'message' => 'Failed to update the office data!'
                            ]);
                        }
                    }
                } else {
                    LOG::info("Failed to copy the record in history table!");
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Failed to copy the record in history table!'
                    ]);
                }
            } else {
                LOG::info("Something went wrong!");
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong!'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal Server Error!'
            ]);
        }
       
    }

    public function destroy($id)
    {
        $data = OfficeDetail::find($id);
        $data->delete();
        alert()->success('Record Deleted successfully')->persistent('Close')->autoclose(3000);
        return redirect()->route('manageOffice');
    }

    public function getZoneList(Request $request)
    {
        $zones = DB::table('asset_master_zones')
            ->select('zone_cd', 'zone_name')
            ->where('dept_cd', $request->department)
            ->get();
        return $zones;
    }

    public function getCircleList(Request $request)
    {
        $circles = DB::table('asset_master_circles')
            ->select('circle_cd', 'circle_name')
            ->where('zone_cd', $request->zone)
            ->get();
        return $circles;
    }

    public function getDivisionList(Request $request)
    {
        $divisions = DB::table('asset_master_divisions')
            ->select('division_cd', 'division_name')
            ->where('circle_cd', $request->circle)
            ->get();
        return $divisions;
    }

    public function getSubDivisionList(Request $request)
    {
        $subDivisions = DB::table('asset_master_sub_divisions')
            ->select('sub_div_cd', 'sub_div_name')
            ->where('div_cd', $request->division)
            ->get();
        return $subDivisions;
    }

    public function getDist()
    {
        $dist_data = SubdistrictofspecificstateDetail::select('district_code', 'district_name')
            ->distinct()
            ->orderBy('district_code', 'asc')
            ->get();
        //print_r(json_encode($dist_data)); die();
        return response()->json($dist_data);
    }

    public function getSubDist(Request $req)
    {
        $dist = $req->dist;
        $sub_dist_data = SubdistrictofspecificstateDetail::select('subdistrict_code', 'subdistrict_name')
            ->where('district_code', $dist)
            ->distinct()
            ->orderBy('subdistrict_code', 'asc')
            ->get();
        return response()->json($sub_dist_data);
    }

    public function getBlock(Request $req)
    {
        $dist = $req->dist;
        $block_data = AllblockstatewithcoveredvillageDetail::select('block_code', 'block_name')
            ->where('district_code', $dist)
            ->distinct()
            ->get();
        return response()->json($block_data);
    }

    public function getVillage(Request $req)
    {
        $block = $req->block;
        $vill_data = AllblockstatewithcoveredvillageDetail::select('village_code', 'village_name')
            ->where('block_code', $block)
            ->distinct()
            ->get();
        return response()->json($vill_data);
    }

    public function getOfficeOnchange(Request $request)
    {
        $val = $request->input('selectedValue');
        if ($val == 'parent') {
            $data =  OfficeDetail::select('*')->where('parent_office', 'Y')->orderBy('id', 'asc')->get();
            $data = $data->map(function ($office) {
                $department_name = DepartmentDetail::where('id', $office->department_id)->value('department_name');
                $office->department_name = $department_name;
                return $office;
            });
        } else if ($val == 'child') {
            $data =  OfficeDetail::select('*')->where('parent_office', 'N')->orderBy('id', 'asc')->get();
            $data = $data->map(function ($office) {
                $department_name = DepartmentDetail::where('id', $office->department_id)->value('department_name');
                $office->department_name = $department_name;
                return $office;
            });
        } else {
            $data =  OfficeDetail::orderBy('id', 'asc')->get();
            $data = $data->map(function ($office) {
                $department_name = DepartmentDetail::where('id', $office->department_id)->value('department_name');
                $office->department_name = $department_name;
                return $office;
            });
        }

        //print_r(json_encode($data));die();
        return response()->json($data);
    }


    // Nitish 21-08-23
    public function listOfOffice(Request $request)
    {
        Log::info('Calling');
        $officeType = $request->input('office_type');
        $departmentType = $request->input('department_type');
        $officeList = OfficeDetail::where('office_type_cd', $officeType)
            ->where('department_id', $departmentType)
            ->get();
        if ($officeList->count() == 0) {
            return response()->json([
                'status' => 404,
                'message' => 'Office not available',
                'offices' => null
            ]);
        }
        return response()->json([
            'status' => 200,
            'message' => 'Office List Fetch Successfully!',
            'offices' => $officeList
        ]);
    }

    public function getParentOffices(Request $request, $officeTypeCd)
    {
        if ($officeTypeCd == 'HQ') {
            $office = [
                [
                    'id' => null,
                    'office_name' => 'Headquarter is its parent itself'
                ],
            ];
            return response()->json($office);
        }
        if ($officeTypeCd == 'ZO') {
            $offices = DB::table('office_details')
                ->select('office_details.id', 'office_details.office_name')
                ->where('office_details.office_type_cd', '=', 'HQ')
                ->get();
        }
        if ($officeTypeCd == 'CO') {
            $offices = DB::table('office_details')
                ->select('office_details.id', 'office_details.office_name', 'asset_master_zones.zone_cd', 'asset_master_zones.zone_name as branch')
                ->join('asset_master_zones', 'office_details.zone_cd', '=', 'asset_master_zones.zone_cd')
                ->where('office_details.office_type_cd', '=', 'ZO')
                ->get();
        }
        if ($officeTypeCd == 'DO') {
            $offices = DB::table('office_details')
                ->select('office_details.id', 'office_details.office_name', 'asset_master_circles.circle_cd', 'asset_master_circles.circle_name as branch')
                ->join('asset_master_circles', 'office_details.circle_cd', '=', 'asset_master_circles.circle_cd')
                ->where('office_details.office_type_cd', '=', 'CO')
                ->get();
        }
        if ($officeTypeCd == 'SDO') {
            $offices = DB::table('office_details')
                ->select('office_details.id', 'office_details.office_name', 'asset_master_divisions.division_cd', 'asset_master_divisions.division_name as branch')
                ->join('asset_master_divisions', 'office_details.division_cd', '=', 'asset_master_divisions.division_cd')
                ->where('office_details.office_type_cd', '=', 'DO')
                ->get();
        }

        return response()->json($offices);
    }
}
