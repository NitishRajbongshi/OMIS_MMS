<?php

namespace App\Http\Controllers\Master\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpansionJointController extends Controller
{
    public function index()
    {
        $joints = DB::table('public.asset_master_expansion_joints')->get();
        return view('master.bridges.expansionJoints', compact('joints'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_expansion_joints')->insert([
                'expn_joint_cd' => strtoupper($request->expn_joint_cd),
                'expn_joint_descr' => $request->expn_joint_descr,
                'created_at' => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Expansion joint added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_expansion_joints')
                ->where('expn_joint_cd', $request->old_expn_joint_cd)
                ->update([
                 //   'expn_joint_cd' => strtoupper($request->expn_joint_cd),
                    'expn_joint_descr' => $request->expn_joint_descr,
                    'updated_at' => now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Expansion joint updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
