<?php

namespace App\Http\Controllers\Master\Construction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WingWallTypeController extends Controller
{
    public function index()
    {
        $wingWallTypes = DB::table('public.asset_master_wing_wall_types')->get();
        return view('master.construction.wingWallTypes', compact('wingWallTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_wing_wall_types')->insert([
                'wing_wall_type_cd' => strtoupper($request->wing_wall_type_cd),
                'wing_wall_type_descr' => $request->wing_wall_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Wing Wall type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error.']);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_wing_wall_types')
                ->where('wing_wall_type_cd', $request->old_cd)
                ->update([
             //       'wing_wall_type_cd' => strtoupper($request->wing_wall_type_cd),
                    'wing_wall_type_descr' => $request->wing_wall_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Wing Wall type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
