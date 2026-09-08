<?php

namespace App\Http\Controllers\Master\Administrative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class DivisionController extends Controller
{
    public function index()
    {
        // Join with circles to display the parent hierarchy in the table
        $divisions = DB::table('public.asset_master_divisions as d')
            ->leftJoin('public.asset_master_circles as c', 'd.circle_cd', '=', 'c.circle_cd')
            ->select('d.*', 'c.circle_name')
            ->orderBy('d.division_name')
            ->get();

        // Data for all required dropdowns
        $states = DB::table('public.asset_master_lgd_state')->get();
        $districts = DB::table('public.asset_master_lgd_district')->get();
        $zones = DB::table('public.asset_master_zones')->get();
        $circles = DB::table('public.asset_master_circles')->get();
        $departments = DB::table('public.department_details')->get();

        return view('master.division.index', compact('divisions', 'states', 'districts', 'zones', 'circles', 'departments'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_divisions')->insert([
                'division_cd'    => $request->division_cd,
                'division_name'  => $request->division_name,
                'circle_cd'      => $request->circle_cd,
                'zone_cd'        => $request->zone_cd,
                'district_cd'    => $request->district_cd,
                'state_cd'       => $request->state_cd,
                'dept_cd'        => $request->dept_cd,
                'div_short_code' => $request->div_short_code,
                'lat'            => $request->lat,
                'lng'            => $request->lng,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Division added successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_divisions')
                ->where('division_cd', $request->division_cd)
                ->update([
                    'division_name' => $request->division_name,
                    'lat'           => $request->lat,
                    'lng'           => $request->lng,
                    'updated_at'    => now(),
                ]);
            return response()->json(['status' => 'success', 'message' => 'Division updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
