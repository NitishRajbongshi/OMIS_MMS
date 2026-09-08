<?php

namespace App\Http\Controllers\Mechanical;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinalizVehicleController extends Controller
{
    public function finalizeSingleData(Request $request) {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $vehicleList = $request->input('assetList');
                $status = DB::table('mechanicals.asset_mech_vehicles_details_draft')
                    ->where('sent_for_finalize', 'N')
                    ->whereIn('vehicle_asset_cd', $vehicleList)
                    ->update([
                        'sent_for_finalize' => 'Y',
                        'sent_for_finalize_on' => Carbon::now(),
                        'sent_for_finalize_by' => Auth::user()->id
                    ]);
                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Successfully data send for finalization!',
                        'result' => $status
                    ]);
                } else {
                    return response()->json([
                        'status' => 503,
                        'message' => 'Vehicle Data not available!',
                        'result' => null
                    ]);
                }
                return $status;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }
}
