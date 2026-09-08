<?php

namespace App\Http\Controllers\Master\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BridgeTypeController extends Controller
{
    public function index()
    {
        $bridgeTypes = DB::table('public.asset_master_bridge_type')->get();
        return view('master.bridges.bridgeType', compact('bridgeTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_bridge_type')->insert([
                'bridge_type_cd' => strtoupper($request->bridge_type_cd),
                'bridge_type_descr' => $request->bridge_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Bridge type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_bridge_type')
                ->where('bridge_type_cd', $request->old_bridge_type_cd)
                ->update([
              //      'bridge_type_cd' => strtoupper($request->bridge_type_cd),
                    'bridge_type_descr' => $request->bridge_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Bridge type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
