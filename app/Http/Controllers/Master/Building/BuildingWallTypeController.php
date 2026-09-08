<?php

namespace App\Http\Controllers\Master\Building;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingWallTypeController extends Controller
{
  public function index()
    {
        $wallTypes = DB::table('buildings.asset_master_building_wall_types')->get();
        return view('master.building.buildingWallTypes', compact('wallTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_wall_types')->insert([
                'wall_type_cd' => strtoupper($request->wall_type_cd),
                'wall_type_descr' => $request->wall_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Wall type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_wall_types')
                ->where('wall_type_cd', $request->old_wall_type_cd)
                ->update([
             //       'wall_type_cd' => strtoupper($request->wall_type_cd),
                    'wall_type_descr' => $request->wall_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Wall type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}

