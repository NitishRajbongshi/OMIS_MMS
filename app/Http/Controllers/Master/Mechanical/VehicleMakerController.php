<?php

namespace App\Http\Controllers\Master\Mechanical;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleMakerController extends Controller
{
   public function index()
    {
        $makers = DB::table('mechanicals.asset_master_vehicle_makers')->get();
        return view('master.mechanical.vehicleMakers', compact('makers'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('mechanicals.asset_master_vehicle_makers')->insert([
                'maker_cd' => strtoupper($request->maker_cd),
                'maker_name' => $request->maker_name
            ]);
            return response()->json(['status' => 'success', 'message' => 'Maker added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('mechanicals.asset_master_vehicle_makers')
                ->where('maker_cd', $request->old_maker_cd)
                ->update([
             //       'maker_cd' => strtoupper($request->maker_cd),
                    'maker_name' => $request->maker_name
                ]);
            return response()->json(['status' => 'success', 'message' => 'Maker updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
