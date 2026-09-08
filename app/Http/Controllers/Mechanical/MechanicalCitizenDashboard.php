<?php

namespace App\Http\Controllers\Mechanical;

use App\Http\Controllers\Controller;
use App\Models\Mechanical\AssetMasterFuelType;
use App\Models\Mechanical\AssetMasterVehicleCondition;
use App\Models\Mechanical\AssetMasterVehicleType;
use App\Models\Mechanical\Master\AssetMasterVehicleMaker;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MechanicalCitizenDashboard extends Controller
{
    public function __construct()
    {
        DB::enableQueryLog();
    }

    public function showEquipment()
    {
        try {
            Log::info('Mechanical Controller. Showing mechanical data to citizen.');
            $conditions = AssetMasterVehicleCondition::all();
            $equipmentDetails = DB::table('mechanicals.asset_mech_equipment_details AS med')
                ->leftJoin('mechanicals.asset_master_vehicle_conditions AS vc', 'med.equipment_condition_cd', '=', 'vc.condition_cd')
                ->select('med.*', 'vc.condition_descr')
                ->orderBy('med.updated_at', 'desc')
                ->get();
            $query = DB::getQueryLog();
            Log::info($query);
            return view('mechanical.citizen.showEquipment', compact(
                'equipmentDetails',
                'conditions'
            ));
        } catch (Exception $e) {
            Log::error("Error message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }

    public function showVehicle()
    {
        try {
            Log::info('Mechanical Controller. Showing mechanical data to citizen.');
            $fuelTypes = AssetMasterFuelType::all();
            $vehTypes = AssetMasterVehicleType::all();
            $conditions = AssetMasterVehicleCondition::all();
            $vehMakers = AssetMasterVehicleMaker::all();
            $vehicleDetails = DB::table('mechanicals.asset_mech_vehicles_details AS mvd')
                ->leftJoin('mechanicals.asset_master_vehicle_conditions AS vc', 'mvd.vehicle_condition', '=', 'vc.condition_cd')
                ->leftJoin('mechanicals.asset_master_fuel_types AS ft', 'mvd.fuel_type', '=', 'ft.fuel_type_cd')
                ->leftJoin('mechanicals.asset_master_vehicle_types AS vt', 'mvd.vehicle_type', '=', 'vt.veh_type_cd')
                ->leftJoin('mechanicals.asset_master_vehicle_makers AS vm', 'mvd.maker', '=', 'vm.maker_cd')
                ->select('mvd.*', 'vc.condition_descr', 'ft.fuel_type_descr', 'vt.veh_type_descr', 'vm.maker_name')
                ->orderBy('mvd.updated_at', 'desc')
                ->get();

            $query = DB::getQueryLog();
            Log::info($query);
            return view('mechanical.citizen.showVehicle', compact(
                'vehicleDetails',
                'fuelTypes',
                'vehTypes',
                'conditions',
                'vehMakers'
            ));
        } catch (Exception $e) {
            Log::error("Error message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return view('error');
        }
    }
}
