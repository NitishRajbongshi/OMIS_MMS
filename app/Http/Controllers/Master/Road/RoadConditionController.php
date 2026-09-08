<?php

namespace App\Http\Controllers\Master\Road;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoadConditionController extends Controller
{
   public function index()
    {
        $roadConditions = DB::table('public.asset_master_road_condition')->get();
        return view('master.road.roadCondition', compact('roadConditions'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_road_condition')->insert([
                'rd_condition_cd' => strtoupper($request->rd_condition_cd),
                'rd_condition_descr' => $request->rd_condition_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Road condition added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_road_condition')
                ->where('rd_condition_cd', $request->old_rd_condition_cd)
                ->update([
              //      'rd_condition_cd' => strtoupper($request->rd_condition_cd),
                    'rd_condition_descr' => $request->rd_condition_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Road condition updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
