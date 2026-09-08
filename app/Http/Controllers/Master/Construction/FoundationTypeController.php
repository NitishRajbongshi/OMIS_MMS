<?php

namespace App\Http\Controllers\Master\Construction;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoundationTypeController extends Controller
{
    
    public function index()
    {
        $foundations = DB::table('public.asset_master_foundation_types')->get();
        return view('master.construction.foundationTypes', compact('foundations'));
    }

   
    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_foundation_types')->insert([
                'foundation_cd' => strtoupper($request->foundation_cd),
                'foundation_descr' => $request->foundation_descr,
                'created_at' => Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Foundation Type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_foundation_types')
                ->where('foundation_cd', $request->old_cd)
                ->update([
             //       'foundation_cd' => strtoupper($request->foundation_cd),
                    'foundation_descr' => $request->foundation_descr,
                    'updated_at' => Carbon::now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Foundation Type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
