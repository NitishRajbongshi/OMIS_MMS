<?php

namespace App\Http\Controllers\Master\Mechanical;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleConditionController extends Controller
{
    public function index()
    {
        $conditions = DB::table('mechanicals.asset_master_vehicle_conditions')->get();
        return view('master.mechanical.vehicleConditions', compact('conditions'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('mechanicals.asset_master_vehicle_conditions')->insert([
                'condition_cd' => strtoupper($request->condition_cd),
                'condition_descr' => $request->condition_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Condition added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: Likely duplicate code.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('mechanicals.asset_master_vehicle_conditions')
                ->where('condition_cd', $request->old_cd)
                ->update([
             //       'condition_cd' => strtoupper($request->condition_cd),
                    'condition_descr' => $request->condition_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Condition updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
