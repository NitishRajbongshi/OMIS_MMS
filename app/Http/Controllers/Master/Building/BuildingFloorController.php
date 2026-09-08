<?php

namespace App\Http\Controllers\Master\Building;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingFloorController extends Controller
{
   public function index()
    {
        $floorTypes = DB::table('buildings.asset_master_building_floor_types')->get();
        return view('master.building.buildingFloorTypes', compact('floorTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_floor_types')->insert([
                'floor_type_cd' => strtoupper($request->floor_type_cd),
                'floor_type_descr' => $request->floor_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Floor type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_floor_types')
                ->where('floor_type_cd', $request->old_floor_type_cd)
                ->update([
                //    'floor_type_cd' => strtoupper($request->floor_type_cd),
                    'floor_type_descr' => $request->floor_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Floor type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}

