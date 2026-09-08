<?php

namespace App\Http\Controllers\Master\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BearingTypeController extends Controller
{
   public function index()
    {
        $bearingTypes = DB::table('public.asset_master_bearing_types')->get();
        return view('master.bridges.bearingType', compact('bearingTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_bearing_types')->insert([
                'bearing_type_cd' => strtoupper($request->bearing_type_cd),
                'bearing_type_descr' => $request->bearing_type_descr,
                'created_at' => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Bearing type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_bearing_types')
                ->where('bearing_type_cd', $request->old_bearing_type_cd)
                ->update([
                  //  'bearing_type_cd' => strtoupper($request->bearing_type_cd),
                    'bearing_type_descr' => $request->bearing_type_descr,
                    'updated_at' => now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Bearing type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
