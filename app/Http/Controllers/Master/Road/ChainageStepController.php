<?php

namespace App\Http\Controllers\Master\Road;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChainageStepController extends Controller
{
    public function index()
    {
        $steps = DB::table('public.asset_master_road_chainage_steps')->get();
        return view('master.road.roadChainageSteps', compact('steps'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_road_chainage_steps')->insert([
                'step_id' => $request->step_id,
                'office_type_cd' => strtoupper($request->office_type_cd),
                'chainage_name' => $request->chainage_name,
                'created_at' => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Step added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate ID or database error']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_road_chainage_steps')
                ->where('step_id', $request->old_step_id)
                ->update([
                 //   'step_id' => $request->step_id,
                    'office_type_cd' => strtoupper($request->office_type_cd),
                    'chainage_name' => $request->chainage_name,
                    'updated_at' => now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Step updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
