<?php

namespace App\Http\Controllers\Master\Building;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingTypeController extends Controller
{
   public function index()
    {
        // Join with building_class to show description in the list
        $buildingTypes = DB::table('buildings.asset_master_building_types as t')
            ->leftJoin('buildings.asset_master_building_class as c', 't.building_class_cd', '=', 'c.building_class_cd')
            ->select('t.*', 'c.building_class_descr')
            ->get();

        $buildingClasses = DB::table('buildings.asset_master_building_class')->get();

        return view('master.building.buildingTypes', compact('buildingTypes', 'buildingClasses'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_types')->insert([
                'building_type_cd' => strtoupper($request->building_type_cd),
                'building_type_descr' => $request->building_type_descr,
                'building_class_cd' => $request->building_class_cd
            ]);
            return response()->json(['status' => 'success', 'message' => 'Building type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: Duplicate code or DB error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_types')
                ->where('building_type_cd', $request->old_building_type_cd)
                ->update([
            //        'building_type_cd' => strtoupper($request->building_type_cd),
                    'building_type_descr' => $request->building_type_descr,
                    'building_class_cd' => $request->building_class_cd
                ]);
            return response()->json(['status' => 'success', 'message' => 'Building type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}