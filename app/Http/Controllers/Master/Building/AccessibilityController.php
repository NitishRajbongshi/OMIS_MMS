<?php

namespace App\Http\Controllers\Master\Building;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessibilityController extends Controller
{
    public function index()
    {
        $accessibilities = DB::table('buildings.asset_master_building_internal_accessibility')->get();
        return view('master.building.buildingAccessibility', compact('accessibilities'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_internal_accessibility')->insert([
                'accessibility_cd' => strtoupper($request->accessibility_cd),
                'accessibility_descr' => $request->accessibility_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Feature added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Description must be unique.']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_internal_accessibility')
                ->where('accessibility_descr', $request->old_accessibility_descr)
                ->update([
             //       'accessibility_cd' => strtoupper($request->accessibility_cd),
                    'accessibility_descr' => $request->accessibility_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Feature updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
