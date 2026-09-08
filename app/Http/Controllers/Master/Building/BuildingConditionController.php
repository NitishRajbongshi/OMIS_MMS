<?php

namespace App\Http\Controllers\Master\Building;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingConditionController extends Controller
{
    public function index()
    {
        $conditions = DB::table('buildings.asset_master_building_conditions')->get();
        return view('master.building.buildingCondition', compact('conditions'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_conditions')->insert([
                'condtion_cd' => strtoupper($request->condtion_cd),
                'condtion_descr' => $request->condtion_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Condition added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: Duplicate code or DB issue']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_conditions')
                ->where('condtion_cd', $request->old_condtion_cd)
                ->update([
                //    'condtion_cd' => strtoupper($request->condtion_cd),
                    'condtion_descr' => $request->condtion_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Condition updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}