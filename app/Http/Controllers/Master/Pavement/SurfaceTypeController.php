<?php

namespace App\Http\Controllers\Master\Pavement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurfaceTypeController extends Controller
{
    public function index()
    {
        // Get surfaces and join with pavement types for better display
        $surfaces = DB::table('public.asset_master_surface_type')->get();
        $pavementTypes = DB::table('public.asset_master_pavement_types')->get();
        
        return view('master.pavements.surfaceTypes', compact('surfaces', 'pavementTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_surface_type')->insert([
                'surface_cd' => strtoupper($request->surface_cd),
                'surface_descr' => $request->surface_descr,
                'pavement_type_cd' => $request->pavement_type_cd
            ]);
            return response()->json(['status' => 'success', 'message' => 'Surface type linked successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: Ensure code is unique.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_surface_type')
                ->where('surface_cd', $request->old_cd)
                ->update([
             //       'surface_cd' => strtoupper($request->surface_cd),
                    'surface_descr' => $request->surface_descr,
                    'pavement_type_cd' => $request->pavement_type_cd
                ]);
            return response()->json(['status' => 'success', 'message' => 'Surface updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed. Check constraints.']);
        }
    }
}
