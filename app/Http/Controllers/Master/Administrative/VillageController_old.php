<?php

namespace App\Http\Controllers\Master\Administrative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VillageController extends Controller
{
    public function index()
    {
        // Fetch Villages with parent names
        $villages = DB::table('public.asset_master_village')->get();

       // dd($villages[0]->village_code);
        // Data for dropdowns
        $states = DB::table('public.asset_master_lgd_state')->get();
        $districts = DB::table('public.asset_master_lgd_district')->get();
        $blocks = DB::table('public.asset_master_block')->get();

        return view('master.vlillage.index', compact('villages', 'states', 'districts', 'blocks'));
    }

    public function store(Request $request)
    {
        try {
            // Fetch names for denormalized columns in your table
            $state = DB::table('public.asset_master_lgd_state')->where('state_code', $request->state_code)->first();
            $district = DB::table('public.asset_master_lgd_district')->where('dist_code', $request->district_code)->first();
            $block = DB::table('public.asset_master_block')->where('block_cd', $request->block_code)->first();

            DB::table('public.asset_master_village')->insert([
                'state_code'    => $request->state_code,
                'state_name'    => $state->state_name ?? null,
                'district_code' => $request->district_code,
                'district_name' => $district->dist_name ?? null,
                'block_code'    => $request->block_code,
                'block_name'    => $block->block_name ?? null,
                'village_code'  => $request->village_code,
                'village_name'  => $request->village_name,
                'census2011_village_code' => $request->census2011_village_code ?? '000000',
            ]);
            
            return response()->json(['status' => 'success', 'message' => 'Village added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $village)
    {
        try {
            DB::table('public.asset_master_village')
                ->where('village_code', $request->village_code)
                ->update([
                    'village_name' => $request->village_name,
                    'census2011_village_code' => $request->census2011_village_code,
                ]);
            return response()->json(['status' => 'success', 'message' => 'Village updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
