<?php

namespace App\Http\Controllers\Master\Road;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoadSubAssetController extends Controller
{
   public function index()
    {
        $subAssets = DB::table('public.asset_master_road_sub_assets')->get();
        return view('master.road.roadSubAsset', compact('subAssets'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_road_sub_assets')->insert([
                'sub_asset_cd' => strtoupper($request->sub_asset_cd),
                'sub_assets_descr' => $request->sub_assets_descr,
                'maker_checker_enabled' => $request->maker_checker_enabled
            ]);
            return response()->json(['status' => 'success', 'message' => 'Sub-Asset added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: Duplicate code or DB constraint violation']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_road_sub_assets')
                ->where('sub_asset_cd', $request->old_sub_asset_cd)
                ->update([
           //         'sub_asset_cd' => strtoupper($request->sub_asset_cd),
                    'sub_assets_descr' => $request->sub_assets_descr,
                    'maker_checker_enabled' => $request->maker_checker_enabled
                ]);
            return response()->json(['status' => 'success', 'message' => 'Sub-Asset updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
