<?php

namespace App\Http\Controllers\Master\Construction;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialTypeController extends Controller
{

    public function index()
    {
        $materialTypes = DB::table('public.asset_master_construction_material_types')->get();
        return view('master.construction.materialTypes', compact('materialTypes'));
    }


    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_construction_material_types')->insert([
                'const_material_type_cd' => strtoupper($request->const_material_type_cd),
                'const_material_type_descr' => $request->const_material_type_descr,
                'created_at' => Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Material Type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or DB error.']);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_construction_material_types')
                ->where('const_material_type_cd', $request->old_cd)
                ->update([
             //       'const_material_type_cd' => strtoupper($request->const_material_type_cd),
                    'const_material_type_descr' => $request->const_material_type_descr,
                    'updated_at' => Carbon::now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Material Type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
