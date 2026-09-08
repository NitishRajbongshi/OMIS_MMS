<?php

namespace App\Http\Controllers\Master\Mechanical;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleTypeController extends Controller
{
    public function index()
    {
        // Query from the mechanicals schema
        $vehicleTypes = DB::table('mechanicals.asset_master_vehicle_types')->get();
        return view('master.mechanical.vehicleType', compact('vehicleTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('mechanicals.asset_master_vehicle_types')->insert([
                'veh_type_cd' => strtoupper($request->veh_type_cd),
                'veh_type_descr' => $request->veh_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Vehicle type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('mechanicals.asset_master_vehicle_types')
                ->where('veh_type_cd', $request->old_veh_type_cd)
                ->update([
             //       'veh_type_cd' => strtoupper($request->veh_type_cd),
                    'veh_type_descr' => $request->veh_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Vehicle type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
