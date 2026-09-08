<?php

namespace App\Http\Controllers\Master\Construction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CDWorkTypeController extends Controller
{

    public function index()
    {
        $cdWorks = DB::table('public.asset_master_rd_cdworks_type')->get();
        return view('master.construction.cdWorkTypes', compact('cdWorks'));
    }
    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_rd_cdworks_type')->insert([
                'cdwork_cd' => strtoupper($request->cdwork_cd),
                'cdwoerk_descr' => $request->cdwoerk_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'CD Work type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_rd_cdworks_type')
                ->where('cdwork_cd', $request->old_cd)
                ->update([
              //      'cdwork_cd' => strtoupper($request->cdwork_cd),
                    'cdwoerk_descr' => $request->cdwoerk_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'CD Work type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed. Check for dependencies.']);
        }
    }
}
