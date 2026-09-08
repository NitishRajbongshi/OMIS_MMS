<?php

namespace App\Http\Controllers\Master\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbutmentTypeController extends Controller
{
    public function index()
    {
        $abutmentTypes = DB::table('public.asset_master_abutment_types')->get();
        return view('master.bridges.abutmentTypes', compact('abutmentTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_abutment_types')->insert([
                'abutment_type_cd' => strtoupper($request->abutment_type_cd),
                'abutment_type_descr' => $request->abutment_type_descr,
                'created_at' => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Abutment type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_abutment_types')
                ->where('abutment_type_cd', $request->old_abutment_type_cd)
                ->update([
       //             'abutment_type_cd' => strtoupper($request->abutment_type_cd),
                    'abutment_type_descr' => $request->abutment_type_descr,
                    'updated_at' => now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Abutment type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}