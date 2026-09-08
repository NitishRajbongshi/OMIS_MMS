<?php

namespace App\Http\Controllers\Master\Construction;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RetainWallTypeController extends Controller
{
       public function index()
    {
        $retainWalls = DB::table('public.asset_master_retain_wall_types')->get();
        return view('master.construction.retainWallTypes', compact('retainWalls'));
    }


    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_retain_wall_types')->insert([
                'retain_type_cd' => strtoupper($request->retain_type_cd),
                'reatain_wall_descr' => $request->reatain_wall_descr,
                'created_at' => Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Retain Wall Type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_retain_wall_types')
                ->where('retain_type_cd', $request->old_cd)
                ->update([
             //       'retain_type_cd' => strtoupper($request->retain_type_cd),
                    'reatain_wall_descr' => $request->reatain_wall_descr,
                    'updated_at' => Carbon::now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Retain Wall Type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
