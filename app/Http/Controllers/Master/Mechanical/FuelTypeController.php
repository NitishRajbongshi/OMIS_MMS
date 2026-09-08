<?php

namespace App\Http\Controllers\Master\Mechanical;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FuelTypeController extends Controller
{
   public function index()
    {
        $fuelTypes = DB::table('mechanicals.asset_master_fuel_types')->get();
        return view('master.mechanical.fuelTypes', compact('fuelTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('mechanicals.asset_master_fuel_types')->insert([
                'fuel_type_cd' => strtoupper($request->fuel_type_cd),
                'fuel_type_descr' => $request->fuel_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Fuel type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Database error or duplicate code.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('mechanicals.asset_master_fuel_types')
                ->where('fuel_type_cd', $request->old_cd)
                ->update([
             //       'fuel_type_cd' => strtoupper($request->fuel_type_cd),
                    'fuel_type_descr' => $request->fuel_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Fuel type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
