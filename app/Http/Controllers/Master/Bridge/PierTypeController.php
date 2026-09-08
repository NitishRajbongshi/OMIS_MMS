<?php

namespace App\Http\Controllers\Master\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PierTypeController extends Controller
{
    public function index()
    {
        $pierTypes = DB::table('public.asset_master_pier_types')->get();
        return view('master.bridges.pierTypes', compact('pierTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_pier_types')->insert([
                'id' => $request->id,
                'pier_type_descr' => $request->pier_type_descr,
                'created_at' => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Pier type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate ID or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_pier_types')
                ->where('id', $request->old_id)
                ->update([
              //      'id' => $request->id,
                    'pier_type_descr' => $request->pier_type_descr,
                    'updated_at' => now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Pier type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}