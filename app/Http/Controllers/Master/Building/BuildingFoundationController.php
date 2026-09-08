<?php

namespace App\Http\Controllers\Master\Building;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingFoundationController extends Controller
{
   public function index()
    {
        $foundationTypes = DB::table('buildings.asset_master_building_foundation_types')->get();
        return view('master.building.buildingfoundation', compact('foundationTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_foundation_types')->insert([
                'foundation_cd' => strtoupper($request->foundation_cd),
                'foundation_descr' => $request->foundation_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Foundation type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_foundation_types')
                ->where('foundation_cd', $request->old_foundation_cd)
                ->update([
           //         'foundation_cd' => strtoupper($request->foundation_cd),
                    'foundation_descr' => $request->foundation_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Foundation type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }

}