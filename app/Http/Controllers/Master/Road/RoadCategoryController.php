<?php

namespace App\Http\Controllers\Master\Road;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoadCategoryController extends Controller
{
   public function index()
    {
        $categories = DB::table('public.asset_master_road_category')->orderBy('rd_catg_cd')->get();
        return view('master.road.index', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_road_category')->insert([
                'rd_catg_cd'         => $request->rd_catg_cd,
                'rd_catg_descr'      => $request->rd_catg_descr,
                'rd_catg_short_code' => $request->rd_catg_short_code,
            ]);
            return response()->json(['status' => 'success', 'message' => 'Road category added successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Check if code already exists']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_road_category')
                ->where('rd_catg_cd', $request->rd_catg_cd)
                ->update([
                    'rd_catg_descr'      => $request->rd_catg_descr,
                    'rd_catg_short_code' => $request->rd_catg_short_code,
                ]);
            return response()->json(['status' => 'success', 'message' => 'Category updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
