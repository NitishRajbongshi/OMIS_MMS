<?php

namespace App\Http\Controllers\Master\Construction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToeWallTypeController extends Controller
{

    public function index()
    {
        $toeWallTypes = DB::table('public.asset_master_toe_wall_types')->get();
        return view('master.construction.toeWallTypes', compact('toeWallTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_toe_wall_types')->insert([
                'toe_wall_type_cd' => strtoupper($request->toe_wall_type_cd),
                'toe_wall_type_descr' => $request->toe_wall_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Toe Wall type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error.']);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_toe_wall_types')
                ->where('toe_wall_type_cd', $request->old_cd)
                ->update([
             //       'toe_wall_type_cd' => strtoupper($request->toe_wall_type_cd),
                    'toe_wall_type_descr' => $request->toe_wall_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Toe Wall type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
