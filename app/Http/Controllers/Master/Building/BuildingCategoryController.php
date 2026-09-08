<?php

namespace App\Http\Controllers\Master\Building;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingCategoryController extends Controller
{
    public function index()
    {
        $categories = DB::table('buildings.asset_master_building_category')->get();
        return view('master.building.buildingCategory', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_category')->insert([
                'building_catg_cd' => strtoupper($request->building_catg_cd),
                'building_catg_descr' => $request->building_catg_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Category added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or DB error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_category')
                ->where('building_catg_cd', $request->old_building_catg_cd)
                ->update([
                 //   'building_catg_cd' => strtoupper($request->building_catg_cd),
                    'building_catg_descr' => $request->building_catg_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Category updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
