<?php

namespace App\Http\Controllers\Level;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class LevelController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    
    function getCircleByZone(Request $request)
    {
        try {
            if (isset($request->id)) {
                $result = DB::table('asset_master_circles')
                    ->select('circle_cd', 'circle_name')
                    ->where('zone_cd', '=', $request->id)
                    ->get();
                if ($result) {
                    return response()->json([
                        'status' => 'success',
                        'result' => $result
                    ]);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Failed to get circle details!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong!'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 'failed',
                'message' => 'Some internal problem occured!'
            ]);
        }
    }

    function getDivisionByCircle(Request $request)
    {
        try {
            if (isset($request->id)) {
                $result = DB::table('asset_master_divisions')
                    ->select('division_cd', 'division_name')
                    ->where('circle_cd', '=', $request->id)
                    ->get();
                if ($result) {
                    return response()->json([
                        'status' => 'success',
                        'result' => $result
                    ]);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Failed to get circle details!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong!'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 'failed',
                'message' => 'Some internal problem occured!'
            ]);
        }
    }

    function getSubDivisionByDivision(Request $request)
    {
        try {
            if (isset($request->id)) {
                $result = DB::table('asset_master_sub_divisions')
                    ->select('sub_div_cd', 'sub_div_name')
                    ->where('div_cd', '=', $request->id)
                    ->get();
                if ($result) {
                    return response()->json([
                        'status' => 'success',
                        'result' => $result
                    ]);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Failed to get circle details!'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Something went wrong!'
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 'failed',
                'message' => 'Some internal problem occured!'
            ]);
        }
    }
}
