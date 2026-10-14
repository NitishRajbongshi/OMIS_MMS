<?php

namespace App\Http\Controllers\Master\Road;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HabitationSubFacilityController extends Controller
{
   public function index()
    {
        $subFacilities = DB::table('public.asset_master_habitation_sub_facilities as sub')
            ->leftJoin('public.asset_master_habitation_facilities as fac', 'sub.facility_id', '=', 'fac.id')
            ->select('sub.*', 'fac.facility_name')
            ->get();

        $facilities = DB::table('public.asset_master_habitation_facilities')
            ->where('is_published', 'Y')
            ->get();
        
        return view('master.road.habitationSubFacilities', compact('subFacilities', 'facilities'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_habitation_sub_facilities')->insert([
                'sub_facility_name' => $request->sub_facility_name,
                'facility_id' => $request->facility_id,
                'is_published' => $request->is_published,
                'created_at' => Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Sub-facility linked!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: check parent facility reference.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_habitation_sub_facilities')
                ->where('id', $request->id)
                ->update([
                    'sub_facility_name' => $request->sub_facility_name,
                    'facility_id' => $request->facility_id,
                    'is_published' => $request->is_published,
                    'updated_at' => Carbon::now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Sub-facility updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
