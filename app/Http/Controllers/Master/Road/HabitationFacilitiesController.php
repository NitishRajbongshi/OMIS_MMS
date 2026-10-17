<?php

namespace App\Http\Controllers\Master\Road;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HabitationFacilitiesController extends Controller
{
    public function index()
    {
        $facilities = DB::table('public.asset_master_habitation_facilities as facilities')
            ->leftJoin('public.asset_master_habitation_score_schemes as schemes', 'facilities.scheme_id', '=', 'schemes.scheme_id')
            ->leftJoin('public.asset_master_habitation_score_calculation_types as calculation_types', 'facilities.caulculation_type_id', '=', 'calculation_types.caulculation_type_id')
            ->select('facilities.*', 'schemes.scheme_name', 'calculation_types.caulculation_type_name')
            ->get();
        $schemes = DB::table('public.asset_master_habitation_score_schemes')
            ->orderBy('scheme_name')
            ->get();
        $calculationTypes = DB::table('public.asset_master_habitation_score_calculation_types')
            ->orderBy('caulculation_type_name')
            ->get();

        return view('master.road.habitationFacilities', compact('facilities', 'schemes', 'calculationTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_name' => 'required|string|max:100',
            'weightage' => 'required|numeric|min:0|max:100',
            'is_published' => 'required|in:Y,N',
            'scheme_id' => 'required|exists:asset_master_habitation_score_schemes,scheme_id',
            'caulculation_type_id' => 'required|exists:asset_master_habitation_score_calculation_types,caulculation_type_id',
        ]);

        try {
            DB::table('public.asset_master_habitation_facilities')->insert([
                'facility_name' => $validated['facility_name'],
                'weightage' => $validated['weightage'],
                'is_published' => $validated['is_published'],
                'scheme_id' => $validated['scheme_id'],
                'caulculation_type_id' => $validated['caulculation_type_id'],
                'created_at' => Carbon::now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Facility added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to save data.']);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'facility_name' => 'required|string|max:100',
            'weightage' => 'required|numeric|min:0|max:100',
            'is_published' => 'required|in:Y,N',
            'scheme_id' => 'required|exists:asset_master_habitation_score_schemes,scheme_id',
            'caulculation_type_id' => 'required|exists:asset_master_habitation_score_calculation_types,caulculation_type_id',
        ]);

        try {
            DB::table('public.asset_master_habitation_facilities')
                ->where('id', $id)
                ->update([
                    'facility_name' => $validated['facility_name'],
                    'weightage' => $validated['weightage'],
                    'is_published' => $validated['is_published'],
                    'scheme_id' => $validated['scheme_id'],
                    'caulculation_type_id' => $validated['caulculation_type_id'],
                    'updated_at' => Carbon::now(),
                ]);
            return response()->json(['status' => 'success', 'message' => 'Facility updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
