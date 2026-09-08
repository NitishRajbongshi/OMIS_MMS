<?php

namespace App\Http\Controllers\Master\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HeadWallController extends Controller
{
    public function index()
    {
        $headWalls = DB::table('public.asset_master_head_walls')->get();
        return view('master.bridges.headWall', compact('headWalls'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_head_walls')->insert([
                'head_wall_cd' => strtoupper($request->head_wall_cd),
                'head_wall_descr' => $request->head_wall_descr,
                'created_at' => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Head Wall added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_head_walls')
                ->where('head_wall_cd', $request->old_head_wall_cd)
                ->update([
                 //   'head_wall_cd' => strtoupper($request->head_wall_cd),
                    'head_wall_descr' => $request->head_wall_descr,
                    'updated_at' => now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Head Wall updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
