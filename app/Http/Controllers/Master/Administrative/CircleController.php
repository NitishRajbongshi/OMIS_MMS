<?php

namespace App\Http\Controllers\Master\Administrative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CircleController extends Controller
{
   public function index()
    {
        // Fetch Circles with Joins to see related names
        $circles = DB::table('public.asset_master_circles as c')
            ->leftJoin('public.asset_master_zones as z', 'c.zone_cd', '=', 'z.zone_cd')
            ->leftJoin('public.asset_master_lgd_district as d', 'c.district_cd', '=', 'd.dist_code')
            ->leftJoin('public.department_details as dept', 'c.dept_cd', '=', 'dept.id')
            ->select('c.*', 'z.zone_name', 'd.dist_name', 'dept.department_name')
            ->orderBy('c.circle_name')
            ->get();

        // Data for dropdowns
        $states = DB::table('public.asset_master_lgd_state')->get();
        $districts = DB::table('public.asset_master_lgd_district')->get();
        $zones = DB::table('public.asset_master_zones')->get();
        $departments = DB::table('public.department_details')->get();

        return view('master.circles.index', compact('circles', 'states', 'districts', 'zones', 'departments'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_circles')->insert([
                'circle_cd'   => $request->circle_cd,
                'circle_name' => $request->circle_name,
                'zone_cd'     => $request->zone_cd,
                'district_cd' => $request->district_cd,
                'state_cd'    => $request->state_cd,
                'dept_cd'     => $request->dept_cd,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Circle created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_circles')
                ->where('circle_cd', $request->circle_cd)
                ->update([
                    'circle_name' => $request->circle_name,
                    'zone_cd'     => $request->zone_cd,
                    'district_cd' => $request->district_cd,
                    'dept_cd'     => $request->dept_cd,
                    'updated_at'  => now(),
                ]);
            return response()->json(['status' => 'success', 'message' => 'Circle updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
