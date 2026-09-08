<?php

namespace App\Http\Controllers\Master\Pavement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PavementConditionController extends Controller
{
  public function index()
    {
        $conditions = DB::table('public.asset_master_pavement_conditions')->get();
        return view('master.pavements.pavementConditions', compact('conditions'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_pavement_conditions')->insert([
                'pv_condition_cd' => strtoupper($request->pv_condition_cd),
                'pv_condition_descr' => $request->pv_condition_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Condition added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Code already exists or database error.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_pavement_conditions')
                ->where('pv_condition_cd', $request->old_cd)
                ->update([
             //       'pv_condition_cd' => strtoupper($request->pv_condition_cd),
                    'pv_condition_descr' => $request->pv_condition_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Condition updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
