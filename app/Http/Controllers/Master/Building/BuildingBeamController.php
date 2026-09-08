<?php

namespace App\Http\Controllers\Master\Building;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingBeamController extends Controller
{
    public function index()
    {
        $beamTypes = DB::table('buildings.asset_master_building_beam_types')->get();
        return view('master.building.buildingBeamTypes', compact('beamTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_beam_types')->insert([
                'beam_type_cd' => strtoupper($request->beam_type_cd),
                'beam_type_descr' => $request->beam_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Beam type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_beam_types')
                ->where('beam_type_cd', $request->old_beam_type_cd)
                ->update([
                  //  'beam_type_cd' => strtoupper($request->beam_type_cd),
                    'beam_type_descr' => $request->beam_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Beam type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed. Code may already exist.']);
        }
    }
}
