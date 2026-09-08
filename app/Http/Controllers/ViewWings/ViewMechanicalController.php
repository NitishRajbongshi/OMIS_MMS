<?php

namespace App\Http\Controllers\ViewWings;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Mechanical\AssetMasterFuelType;
use App\Models\Mechanical\AssetMasterVehicleType;
use App\Models\Mechanical\Master\AssetMasterEquipmentCondition;

class ViewMechanicalController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function showEquipment()
    {
        try {
            DB::enableQueryLog();
            Log::info('View wing controller: ');

            $conditions = AssetMasterEquipmentCondition::all();

            $query = DB::getQueryLog();
            Log::info($query);

            return view('wings.mechanical.equipment', compact('conditions'));
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }

    public function showVehicle()
    {
        try {
            DB::enableQueryLog();
            Log::info('View wing controller: ');

            $conditions = AssetMasterEquipmentCondition::all();
            $vehTypes = AssetMasterVehicleType::all();
            $fuelTypes = AssetMasterFuelType::all();

            $query = DB::getQueryLog();
            Log::info($query);

            return view('wings.mechanical.vehicle', compact('conditions', 'vehTypes', 'fuelTypes'));
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }
}
