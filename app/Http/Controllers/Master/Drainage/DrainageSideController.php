<?php

namespace App\Http\Controllers\Master\Drainage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DrainageSideController extends Controller
{
    public function index()
    {
        $sides = DB::table('public.asset_master_drainage_sides')->get();
        return view('master.drainage.drainageSides', compact('sides'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_drainage_sides')->insert([
                'drainage_side_cd' => strtoupper($request->drainage_side_cd),
                'drainage_side_descr' => $request->drainage_side_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Side added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_drainage_sides')
                ->where('drainage_side_cd', $request->old_cd)
                ->update([
             //       'drainage_side_cd' => strtoupper($request->drainage_side_cd),
                    'drainage_side_descr' => $request->drainage_side_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Side updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
