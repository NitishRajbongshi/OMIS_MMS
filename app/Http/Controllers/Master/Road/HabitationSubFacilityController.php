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
            ->leftJoin('public.asset_master_habitation_score_schemes as schemes', 'sub.scheme_id', '=', 'schemes.scheme_id')
            ->select('sub.*', 'fac.facility_name', 'schemes.scheme_name')
            ->get();

        $facilities = DB::table('public.asset_master_habitation_facilities')
            ->where('is_published', 'Y')
            ->get();
        $schemes = DB::table('public.asset_master_habitation_score_schemes')
            ->orderBy('scheme_name')
            ->get();

        return view('master.road.habitationSubFacilities', compact('subFacilities', 'facilities', 'schemes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sub_facility_name' => 'required|string|max:100',
            'facility_id' => 'required|exists:asset_master_habitation_facilities,id',
            'weightage' => 'required|numeric|min:0|max:100',
            'is_published' => 'required|in:Y,N',
            'scheme_id' => 'required|exists:asset_master_habitation_score_schemes,scheme_id',
        ]);

        try {
            $facilityWeightage = DB::table('public.asset_master_habitation_facilities')
                ->where('id', $validated['facility_id'])
                ->value('weightage');

            if ($validated['weightage'] > $facilityWeightage) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Sub-facility weightage cannot be greater than parent facility weightage (' . $facilityWeightage . ').'
                ]);
            }

            DB::table('public.asset_master_habitation_sub_facilities')->insert([
                'sub_facility_name' => $validated['sub_facility_name'],
                'facility_id' => $validated['facility_id'],
                'weightage' => $validated['weightage'],
                'is_published' => $validated['is_published'],
                'scheme_id' => $validated['scheme_id'],
                'created_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Sub-facility linked!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: check parent facility reference.']);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sub_facility_name' => 'required|string|max:100',
            'facility_id' => 'required|exists:asset_master_habitation_facilities,id',
            'weightage' => 'required|numeric|min:0|max:100',
            'is_published' => 'required|in:Y,N',
            'scheme_id' => 'required|exists:asset_master_habitation_score_schemes,scheme_id',
        ]);

        try {
            $facility = DB::table('public.asset_master_habitation_facilities')
                ->where('id', $validated['facility_id'])
                ->first(['facility_name', 'weightage']);

            if ($validated['weightage'] > $facility->weightage) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Sub-facility weightage cannot be greater than parent facility "'
                    . $facility->facility_name
                    . '" weightage (' . $facility->weightage . ').'
                ]);
            }

            DB::table('public.asset_master_habitation_sub_facilities')
                ->where('id', $id)
                ->update([
                    'sub_facility_name' => $validated['sub_facility_name'],
                    'facility_id' => $validated['facility_id'],
                    'weightage' => $validated['weightage'],
                    'is_published' => $validated['is_published'],
                    'scheme_id' => $validated['scheme_id'],
                    'updated_at' => Carbon::now(),
                ]);
            return response()->json(['status' => 'success', 'message' => 'Sub-facility updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
