<?php

namespace App\Http\Controllers\Master\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OfficeDetailsController extends Controller
{
    public function index()
    {
        $offices = DB::table('projects.prm_site_incharge_office_details as o')
            ->leftJoin('public.asset_master_lgd_district as d', 'o.district_cd', '=', 'd.dist_code')
            ->leftJoin('public.asset_master_lgd_state as s', 'o.state_cd', '=', 's.state_code')
            ->select('o.*', 'd.dist_name', 's.state_name')
            ->get();

        $states = DB::table('public.asset_master_lgd_state')->get();
        $districts = DB::table('public.asset_master_lgd_district')->get();

        return view('master.projects.siteIncharge', compact('offices', 'states', 'districts'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('projects.prm_site_incharge_office_details')->insert([
                'office_cd' => $request->office_cd,
                'office_name' => $request->office_name,
                'contact_person_name' => $request->contact_person_name,
                'ph_no' => $request->ph_no,
                'address_line1' => $request->address_line1,
                'district_cd' => $request->district_cd,
                'state_cd' => $request->state_cd,
                'pin_code' => $request->pin_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'is_published' => $request->has('is_published') ? 'Y' : 'N',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Office details saved!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('projects.prm_site_incharge_office_details')
                ->where('office_cd', $request->old_office_cd)
                ->update([
                 //   'office_cd' => $request->office_cd,
                    'office_name' => $request->office_name,
                    'contact_person_name' => $request->contact_person_name,
                    'ph_no' => $request->ph_no,
                    'updated_at' => now(),
                ]);
            return response()->json(['status' => 'success', 'message' => 'Office updated!']);
        } catch (\Exception $e) {
            Log::info('Update failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
