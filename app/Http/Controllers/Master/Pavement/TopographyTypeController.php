<?php

namespace App\Http\Controllers\Master\Pavement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopographyTypeController extends Controller
{
   public function index()
    {
        $topographies = DB::table('public.asset_master_pavement_topography_types')->get();
        return view('master.pavements.topographyTypes', compact('topographies'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_pavement_topography_types')->insert([
                'topography_cd' => strtoupper($request->topography_cd),
                'topography_descr' => $request->topography_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Topography added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_pavement_topography_types')
                ->where('topography_cd', $request->old_cd)
                ->update([
             //       'topography_cd' => strtoupper($request->topography_cd),
                    'topography_descr' => $request->topography_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Topography updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
