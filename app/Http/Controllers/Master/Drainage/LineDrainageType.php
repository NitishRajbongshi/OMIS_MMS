<?php

namespace App\Http\Controllers\Master\Drainage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LineDrainageType extends Controller
{
    public function index()
    {
        $lineTypes = DB::table('public.asset_master_drainage_line_drainage_types')->get();
        return view('master.drainage.lineDrainageType', compact('lineTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_drainage_line_drainage_types')->insert([
                'line_drainage_type_cd' => $request->line_drainage_type_cd,
                'line_drainage_type_descr' => $request->line_drainage_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Line drainage type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate ID or database error']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_drainage_line_drainage_types')
                ->where('line_drainage_type_cd', $request->old_cd)
                ->update([
             //       'line_drainage_type_cd' => $request->line_drainage_type_cd,
                    'line_drainage_type_descr' => $request->line_drainage_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Line drainage type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
