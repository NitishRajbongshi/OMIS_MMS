<?php

namespace App\Http\Controllers\Master\Administrative;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DistrictController extends Controller
{
    //
    public function index()
    {
        // Fetch districts joined with state names for better display
        $districts = DB::table('public.asset_master_lgd_district as d')
            ->select('d.*', 's.state_name')
            ->leftJoin('public.asset_master_lgd_state as s', 'd.state_code', '=', 's.state_code')
            ->orderBy('d.dist_name', 'asc')
            ->get();

        // Fetch states for the dropdown in the 'Add' form
        $states = DB::table('public.asset_master_lgd_state')
            ->orderBy('state_name', 'asc')
            ->get();

        return view('master.district.index', compact('districts', 'states'));
    }

    /**
     * Store a new district
     */
    public function store(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'dist_code' => ['required', 'unique:asset_master_lgd_district,dist_code'],
                'dist_name' => 'required|max:100',
                'state_code' => 'required',
            ]);

            DB::table('public.asset_master_lgd_district')->insert([
                'dist_code' => $request->dist_code,
                'dist_name' => $request->dist_name,
                'state_code' => $request->state_code,
                'dist_short_code' => $request->dist_short_code,
                'census2011_dist_code' => $request->census2011_dist_code ?? '000',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'District added successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error("Error adding district: " . $e->getMessage());
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to add district. Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update existing district
     */
    public function update(Request $request)
    {
        try {
            // dist_code is the primary key used to locate the record
            DB::table('public.asset_master_lgd_district')
                ->where('dist_code', $request->dist_code)
                ->update([
                    'dist_name' => $request->dist_name,
                    'dist_short_code' => $request->dist_short_code,
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'District updated successfully!'
            ]);

        } catch (\Exception $e) {
            Log::error("Error updating district: " . $e->getMessage());
            return response()->json([
                'status' => 'failed',
                'message' => 'Update failed. Please try again.'
            ]);
        }
    }
}

