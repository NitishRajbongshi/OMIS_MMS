<?php

namespace App\Http\Controllers\Designation;

use Exception;
use Carbon\Carbon;
use App\Models\DesgDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DesignationController extends Controller
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
            $desg_details = DesgDetail::orderBy('desg_name')->get();
            $query = DB::getQueryLog();
            Log::info($query);
            return view('designation.index', compact(
                'desg_details',
            ));
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }

    public function store(Request $request)
    {
        try {
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'desg_name' => 'required|string|max:255',
                ], [
                    'desg_name.required' => 'This field is required'
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'validationFails',
                        'error' => $validator->errors()
                    ]);
                } else {
                    $isNameExist = DesgDetail::whereRaw('LOWER(desg_name) = ?', [strtolower($request->desg_name)])->exists();

                    if (!$isNameExist) {
                        $DesgDetail = new DesgDetail();
                        $DesgDetail->desg_name = $request->desg_name;
                        $DesgDetail->created_at = Carbon::now();
                        $DesgDetail->updated_at = Carbon::now();

                        $DesgDetail->save();

                        return response()->json([
                            'message' => 'success',
                            'request' => 'Data Saved Successfully',
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'duplicate'
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'messege' => 'error',
                'request' => 'Something Went Wrong',
            ]);
        }
    }

    public function update(Request $request)
    {
        try {
            if ($request->ajax()) {
                $validator = Validator::make($request->all(), [
                    'desg_name' => 'required|string|max:255',
                ], [
                    'desg_name.required' => 'This field is required'
                ]);
                if ($validator->fails()) {

                    return response()->json([
                        'message' => 'validationFails',
                        'error' => $validator->errors()
                    ]);
                } else {
                    $isNameExist = DesgDetail::whereRaw('LOWER(desg_name) = ?', [strtolower($request->desg_name)])->exists();

                    if (!$isNameExist) {
                        $desgdata = DesgDetail::find($request->id);
                        $desgdata->desg_name = $request->desg_name;
                        $desgdata->created_at = Carbon::now();
                        $desgdata->updated_at = Carbon::now();
                        $desgdata->save();
                        return response()->json([
                            'message' => 'success'
                        ]);
                    } else {
                        return response()->json([
                            'message' => 'duplicate'
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return $e;
        }
    }

    public function destroy($id)
    {
        $data = DesgDetail::find($id);
        $data->delete();
        alert()->success('Record Deleted successfully')->persistent('Close')->autoclose(3000);
        return redirect()->route('manageDesignation');
    }

    public function getDesignationHistory(Request $request)
    {
        try {
            DB::enableQueryLog();
            Log::info('Designation controller: ');
            $desgId = $request->id;
            $userLists = DB::table('user_movements')
                ->select(
                    'user_movements.*',
                    'department_details.department_name',
                    'desg_details.desg_name',
                    'asset_master_office_types.office_type_desc',
                    'office_details.office_name',
                )
                ->leftJoin('department_details', 'user_movements.user_dept', '=', 'department_details.id')
                ->leftJoin('desg_details', 'user_movements.user_desg', '=', 'desg_details.id')
                ->leftJoin('asset_master_office_types', 'user_movements.user_office_type_cd', '=', 'asset_master_office_types.office_type_cd')
                ->leftJoin('office_details', 'user_movements.user_office', '=', 'office_details.id')
                ->where('user_desg', $desgId)
                ->orderBy('id', 'desc')
                ->get();
            $degnDetails = DB::table('desg_details')
                ->select('desg_name')
                ->where('id', $desgId)
                ->get()->first();
            $query = DB::getQueryLog();
            Log::info($query);
            $desgName = $degnDetails->desg_name;
            return view('designation.userList', ['userLists' => $userLists, 'desgName' => $desgName]);
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return view('error');
        }
    }
}
