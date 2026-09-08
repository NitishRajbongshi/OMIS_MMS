<?php

namespace App\Http\Controllers\Master\Construction;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConstructionTypeController extends Controller
{
    public function index()
    {
        $types = DB::table('public.asset_master_construction_types')->get();
        return view('master.construction.constructionTypes', compact('types'));
    }
    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_construction_types')->insert([
                'construction_type_cd' => strtoupper($request->construction_type_cd),
                'construction_type_descr' => $request->construction_type_descr,
                'created_at' => Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Type added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_construction_types')
                ->where('construction_type_cd', $request->old_cd)
                ->update([
               //     'construction_type_cd' => strtoupper($request->construction_type_cd),
                    'construction_type_descr' => $request->construction_type_descr,
                    'updated_at' => Carbon::now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Type updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed. Check for duplicate codes.']);
        }
    }
}
