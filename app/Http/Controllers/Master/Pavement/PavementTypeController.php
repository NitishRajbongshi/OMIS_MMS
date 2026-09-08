<?php

namespace App\Http\Controllers\Master\Pavement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PavementTypeController extends Controller
{
    public function index()
    {
        $pavementTypes = DB::table('public.asset_master_pavement_types')->get();
        return view('master.pavements.pavementTypes', compact('pavementTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_pavement_types')->insert([
                    'pavement_type_cd' => strtoupper($request->pavement_type_cd),
                    'pavement_type_descr' => $request->pavement_type_descr,
                'created_at' => Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Pavement Type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_pavement_types')
                ->where('pavement_type_cd', $request->old_cd)
                ->update([
                //    'pavement_type_cd' => strtoupper($request->pavement_type_cd),
                    'pavement_type_descr' => $request->pavement_type_descr,
                    'updated_at' => Carbon::now()->toDateTimeString() // Saving as string per schema
                ]);
            return response()->json(['status' => 'success', 'message' => 'Pavement Type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
