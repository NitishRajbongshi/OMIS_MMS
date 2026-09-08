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
        $facilities = DB::table('public.asset_master_habitation_facilities')->get();
        return view('master.road.habitationFacilities', compact('facilities'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_habitation_facilities')->insert([
                'facility_name' => $request->facility_name,
                'is_published' => $request->is_published,
                'created_at' => Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Facility added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to save data.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_habitation_facilities')
                ->where('id', $request->id)
                ->update([
                    'facility_name' => $request->facility_name,
                    'is_published' => $request->is_published,
                    'updated_at' => Carbon::now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Facility updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
