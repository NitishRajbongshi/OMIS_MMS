<?php

namespace App\Http\Controllers\Master\Administrative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZoneController extends Controller
{
    public function index()
    {
        // Fetch Zones with Joins for Table View
        $zones = DB::table('public.asset_master_zones as z')
            ->leftJoin('public.asset_master_lgd_district as d', 'z.district_cd', '=', 'd.dist_code')
            ->leftJoin('public.department_details as dept', 'z.dept_cd', '=', 'dept.id')
            ->select('z.*', 'd.dist_name', 'dept.department_name')
            ->get();

        // Data for dropdowns
        $states = DB::table('public.asset_master_lgd_state')->get();
        $districts = DB::table('public.asset_master_lgd_district')->get();
        $departments = DB::table('public.department_details')->get();

        return view('master.zones.index', compact('zones', 'states', 'districts', 'departments'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_zones')->insert([
                'zone_cd'     => $request->zone_cd,
                'zone_name'   => $request->zone_name,
                'district_cd' => $request->district_cd,
                'state_cd'    => $request->state_cd,
                'dept_cd'     => $request->dept_cd,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Zone added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_zones')
                ->where('zone_cd', $request->zone_cd)
                ->update([
                    'zone_name'  => $request->zone_name,
                    'district_cd'=> $request->district_cd,
                    'dept_cd'    => $request->dept_cd,
                    'updated_at' => now(),
                ]);
            return response()->json(['status' => 'success', 'message' => 'Zone updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}

