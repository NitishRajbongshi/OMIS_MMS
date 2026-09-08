<?php

namespace App\Http\Controllers\Master\Administrative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlockController extends Controller
{
      public function index()
    {
        // Fetch Blocks with District and State names
        $blocks = DB::table('public.asset_master_block as b')
            ->leftJoin('public.asset_master_lgd_district as d', 'b.district_cd', '=', 'd.dist_code')
            ->leftJoin('public.asset_master_lgd_state as s', 'b.state_cd', '=', 's.state_code')
            ->select('b.*', 'd.dist_name', 's.state_name')
            ->get();

        $states = DB::table('public.asset_master_lgd_state')->get();
        $districts = DB::table('public.asset_master_lgd_district')->get();

        return view('master.block.index', compact('blocks', 'states', 'districts'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_block')->insert([
                'block_cd'    => $request->block_cd,
                'block_name'  => $request->block_name,
                'district_cd' => $request->district_cd,
                'state_cd'    => $request->state_cd,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Block added successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to add block']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_block')
                ->where('block_cd', $request->block_cd)
                ->update([
                    'block_name' => $request->block_name,
                    'updated_at' => now(),
                ]);
            return response()->json(['status' => 'success', 'message' => 'Block updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
