<?php

namespace App\Http\Controllers\Master\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PileTypeController extends Controller
{
   public function index()
    {
        $pileTypes = DB::table('public.asset_master_pile_types')->get();
        return view('master.bridges.pileTypes', compact('pileTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_pile_types')->insert([
                'pile_type_cd' => strtoupper($request->pile_type_cd),
                'pile_type_descr' => $request->pile_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Pile type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_pile_types')
                ->where('pile_type_cd', $request->old_pile_type_cd)
                ->update([
               //     'pile_type_cd' => strtoupper($request->pile_type_cd),
                    'pile_type_descr' => $request->pile_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Pile type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}