<?php

namespace App\Http\Controllers\Master\Drainage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DrainageTypeController extends Controller
{
    public function index()
    {
        $drainageTypes = DB::table('public.asset_master_drainage_types')->get();
        return view('master.drainage.drainageTypes', compact('drainageTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_drainage_types')->insert([
                'drainage_cd' => strtoupper($request->drainage_cd),
                'drainage_descr' => $request->drainage_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Drainage type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_drainage_types')
                ->where('drainage_cd', $request->old_drainage_cd)
                ->update([
             //       'drainage_cd' => strtoupper($request->drainage_cd),
                    'drainage_descr' => $request->drainage_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Drainage type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
