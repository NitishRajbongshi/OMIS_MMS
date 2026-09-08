<?php

namespace App\Http\Controllers\Master\Administrative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubDivisionController extends Controller
{
   public function index()
    {
        $sub_divisions = DB::table('public.asset_master_sub_divisions as sd')
            ->leftJoin('public.asset_master_divisions as d', 'sd.div_cd', '=', 'd.division_cd')
            ->leftJoin('public.asset_master_circles as c', 'sd.circle_cd', '=', 'c.circle_cd')
            ->leftJoin('public.asset_master_zones as z', 'sd.zone_cd', '=', 'z.zone_cd')
            ->leftJoin('public.asset_master_lgd_state as s', 'sd.state_cd', '=', 's.state_code')
            ->leftJoin('public.asset_master_lgd_district as dist', 'sd.district_cd', '=', 'dist.dist_code')
            ->select('sd.*', 'd.division_name', 'c.circle_name', 'z.zone_name', 's.state_name', 'dist.dist_name')
            ->get();

        $divisions = DB::table('public.asset_master_divisions')->get();
        $departments = DB::table('public.department_details')->get();
        $districts = DB::table('public.asset_master_lgd_district')->get();

        return view('master.division.subDivision', compact('sub_divisions', 'divisions', 'departments', 'districts'));
    }
    public function getHierarchy($div_cd)
    {
        // Fetches the circle, zone, and state associated with a division
        $data = DB::table('public.asset_master_divisions as d')
            ->join('public.asset_master_circles as c', 'd.circle_cd', '=', 'c.circle_cd')
            ->join('public.asset_master_zones as z', 'c.zone_cd', '=', 'z.zone_cd')
            ->join('public.asset_master_lgd_state as s', 'z.state_cd', '=', 's.state_code')
            ->where('d.division_cd', $div_cd)
            ->select('c.circle_cd', 'c.circle_name', 'z.zone_cd', 'z.zone_name', 's.state_code as state_cd', 's.state_name')
            ->first();

        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_sub_divisions')->insert([
                'sub_div_cd'   => $request->sub_div_cd,
                'sub_div_name' => $request->sub_div_name,
                'div_cd'       => $request->div_cd,
                'circle_cd'    => $request->circle_cd,
                'zone_cd'      => $request->zone_cd,
                'state_cd'     => $request->state_cd,
                'district_cd'  => $request->district_cd,
                'dept_cd'      => $request->dept_cd,
                'created_at'   => now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Sub-Division created with full hierarchy!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Database Error']);
        }
    }
    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_sub_divisions')
                ->where('sub_div_cd', $request->old_sub_div_cd)
                ->update([
                    'sub_div_cd'   => $request->sub_div_cd,
                    'sub_div_name' => $request->sub_div_name,
                    'updated_at'   => now(),
                ]);
            return response()->json(['status' => 'success', 'message' => 'Sub-Division updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }

     public function getDivisionHierarchy($div_cd)
    {
        // Fetches the circle, zone, and state associated with a division
        $data = DB::table('public.asset_master_divisions as d')
            ->leftJoin('public.asset_master_circles as c', 'd.circle_cd', '=', 'c.circle_cd')
            ->leftJoin('public.asset_master_zones as z', 'd.zone_cd', '=', 'z.zone_cd')
            ->leftJoin('public.asset_master_lgd_state as s', 'd.state_cd', '=', 's.state_code')
            ->where('d.division_cd', $div_cd)
            ->select('c.circle_cd', 'c.circle_name', 'z.zone_cd', 'z.zone_name', 's.state_code as state_cd', 's.state_name')
            ->first();

        return response()->json($data);
    }

}