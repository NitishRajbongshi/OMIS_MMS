<?php

namespace App\Http\Controllers\Master\Building;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingClassController extends Controller
{
    public function index()
    {
        $classes = DB::table('buildings.asset_master_building_class')->get();
        return view('master.building.buildingClass', compact('classes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_class')->insert([
                'building_class_cd' => strtoupper($request->building_class_cd),
                'building_class_descr' => $request->building_class_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Class added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_class')
                ->where('building_class_cd', $request->old_building_class_cd)
                ->update([
                //    'building_class_cd' => strtoupper($request->building_class_cd),
                    'building_class_descr' => $request->building_class_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Class updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed. Check for duplicate codes.']);
        }
    }
}