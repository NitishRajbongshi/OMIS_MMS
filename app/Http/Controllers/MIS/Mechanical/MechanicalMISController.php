<?php

namespace App\Http\Controllers\MIS\Mechanical;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class MechanicalMISController extends Controller
{
    public function searchEquipment(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $condition = $request->input('condition');
                $purchasedYear = $request->input('purchased_year');
                $warranty = $request->input('warranty');
                $baseQuery = DB::table('mechanicals.asset_mech_equipment_details AS med')
                    ->leftJoin('mechanicals.asset_master_vehicle_conditions AS vc', 'med.equipment_condition_cd', '=', 'vc.condition_cd')
                    ->select('med.*', 'vc.condition_descr');

                if ($condition != 'null') {
                    $baseQuery->where('med.equipment_condition_cd', $condition);
                }
                if ($warranty != 'null') {
                    $baseQuery->where('med.is_under_waranty', $warranty);
                }
                if ($purchasedYear != 'null') {
                    $baseQuery->where('med.purchase_year', $purchasedYear);
                }

                $equipmentDetails = $baseQuery->get();

                if ($equipmentDetails) {
                    if($equipmentDetails->count() == 0) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Data not available!',
                            'result' => $equipmentDetails
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Building data fetched successfully!',
                            'result' => $equipmentDetails
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Building details not available!',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }

    public function searchVehicle(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $vehType = $request->input('veh_type');
                $fuelType = $request->input('fuel_type');
                $baseQuery = DB::table('mechanicals.asset_mech_vehicles_details AS mvd')
                    ->leftJoin('mechanicals.asset_master_vehicle_conditions AS vc', 'mvd.vehicle_condition', '=', 'vc.condition_cd')
                    ->leftJoin('mechanicals.asset_master_fuel_types AS ft', 'mvd.fuel_type', '=', 'ft.fuel_type_cd')
                    ->leftJoin('mechanicals.asset_master_vehicle_types AS vt', 'mvd.vehicle_type', '=', 'vt.veh_type_cd')
                    ->leftJoin('mechanicals.asset_master_vehicle_makers AS vm', 'mvd.maker', '=', 'vm.maker_cd')
                    ->select('mvd.*', 'vc.condition_descr', 'ft.fuel_type_descr', 'vt.veh_type_descr', 'vm.maker_name');

                if ($vehType != 'null') {
                    $baseQuery->where('mvd.vehicle_type', $vehType);
                }
                if ($fuelType != 'null') {
                    $baseQuery->where('mvd.fuel_type', $fuelType);
                }
                $vehicleDetails = $baseQuery->get();

                if ($vehicleDetails) {
                    if($vehicleDetails->count() == 0) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Data not available!',
                            'result' => $vehicleDetails
                        ]);
                    }
                    else {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Vehicle data fetched successfully!',
                            'result' => $vehicleDetails
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Vehicle details not available!',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }
}
