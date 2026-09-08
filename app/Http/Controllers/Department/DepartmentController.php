<?php

namespace App\Http\Controllers\Department;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DepartmentDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function GetAddDepartment()
    {
        try {
            DB::enableQueryLog();
            Log::info('Department controller: ');
            $d_details = DepartmentDetail::orderBy('department_name')->get();
            return view('department.index', compact(
                'd_details',
            ));
            $query = DB::getQueryLog();
            Log::info($query);
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }


    public function AddDepartment(Request $request)
    {
        try {
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'department_name' => 'required|string|max:255',
                ], [
                    'department_name.required' => 'This field is required'
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Validation error occured!',
                        'error' => $validator->errors()
                    ]);
                } else {
                    $isNameExist = DepartmentDetail::whereRaw('LOWER(department_name) = ?', [strtolower($request->department_name)])->exists();

                    if (!$isNameExist) {
                        $DeptDetail = new DepartmentDetail();
                        $DeptDetail->department_name = $request->department_name;
                        $DeptDetail->created_at = Carbon::now();
                        $DeptDetail->updated_at = Carbon::now();
                        $DeptDetail->save();

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Departement saved successfully!'
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'failed',
                            'message' => 'Department name already exist!'
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 'failed',
                'messege' => 'Internal server error!',
                'error' => $e,
            ]);
        }
    }

    public function updateDepartment(Request $request)
    {
        try {
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'department_name' => 'required|string|max:255',
                ], [
                    'department_name.required' => 'This field is required'
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Validation error occured!',
                        'error' => $validator->errors()
                    ]);
                } else {
                    $isNameExist = DepartmentDetail::whereRaw('LOWER(department_name) = ?', [strtolower($request->department_name)])->exists();

                    if (!$isNameExist) {
                        $deptdata = DepartmentDetail::find($request->id);
                        $deptdata->department_name = $request->department_name;
                        $deptdata->created_at = Carbon::now();
                        $deptdata->updated_at = Carbon::now();
                        $deptdata->save();

                        // $U_id = Auth::user()->id;
                        // $ipAddress = $request->ip();
                        // $desc = 'Department updated by id: ' . $U_id . '. The updated department id is: ' . $deptdata->id;
                        // MyHelper::upActivityLog(Auth::user()->id, 'Department-' . $deptdata->id, $desc, $ipAddress);

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Departement saved successfully!'
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'failed',
                            'message' => 'Department name already exist!'
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 'failed',
                'messege' => 'Internal server error!',
                'error' => $e,
            ]);
        }
    }

    public function deleteDepartment($id)
    {
        $data = DepartmentDetail::find($id);
        $data->delete();
        alert()->success('Record Deleted successfully')->persistent('Close')->autoclose(3000);
        return redirect()->route('GetAddDepartment');
    }
}
