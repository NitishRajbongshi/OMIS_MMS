<?php

namespace App\Http\Controllers\Master\Building;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuildingAccessController extends Controller
{
    public function index()
    {
        $accessTypes = DB::table('buildings.asset_master_building_access_types')->get();
        return view('master.building.buildingAcessType', compact('accessTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_access_types')->insert([
                'access_type_cd' => strtoupper($request->access_type_cd),
                'access_type_descr' => $request->access_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Access type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('buildings.asset_master_building_access_types')
                ->where('access_type_cd', $request->old_access_type_cd)
                ->update([
                   // 'access_type_cd' => strtoupper($request->access_type_cd),
                    'access_type_descr' => $request->access_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Access type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
