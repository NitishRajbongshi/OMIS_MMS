<?php

namespace App\Http\Controllers\Master\Building;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingColumnController extends Controller
{
   public function index()
    {
        $columnTypes = DB::table('buildings.asset_master_building_column_types')->get();
        return view('master.building.buildingColumnTypes', compact('columnTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_column_types')->insert([
                'column_type_cd' => strtoupper($request->column_type_cd),
                'column_type_descr' => $request->column_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Column type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_column_types')
                ->where('column_type_cd', $request->old_column_type_cd)
                ->update([
             //       'column_type_cd' => strtoupper($request->column_type_cd),
                    'column_type_descr' => $request->column_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Column type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}