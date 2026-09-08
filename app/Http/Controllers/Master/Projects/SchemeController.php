<?php

namespace App\Http\Controllers\Master\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SchemeController extends Controller
{
    /**
     * Display the list of schemes
     */
    public function index()
    {
        // Fetching directly from the schema.table
        $schemes = DB::table('projects.prm_scheme_details')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('master.projects.schemeDetails', compact('schemes'));
    }

    /**
     * Store a new scheme
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scheme_code'  => 'required|max:50|unique:pgsql_pms.prm_scheme_details,scheme_code',
            'scheme_name'  => 'required|max:100',
            'total_budget' => 'nullable|numeric',
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        try {

            DB::table('projects.prm_scheme_details')->insert([
                'scheme_code'   => strtoupper($request->scheme_code),
                'scheme_name'   => $request->scheme_name,
                'description'   => $request->description,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'total_budget'  => $request->total_budget,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Scheme saved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update an existing scheme
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
           // 'scheme_code'  => 'required|max:50|unique:pgsql_pms.prm_scheme_details,scheme_code,' . $id . ',scheme_id',
            'scheme_name'  => 'required|max:100',
            'total_budget' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        try {
            $affected = DB::table('projects.prm_scheme_details')
                ->where('scheme_id', $id)
                ->update([
                    'scheme_name'   => $request->scheme_name,
                    'description'   => $request->description,
                    'start_date'    => $request->start_date,
                    'end_date'      => $request->end_date,
                    'total_budget'  => $request->total_budget,
                    'is_published'  => $request->is_published,
                    'updated_at'    => Carbon::now(), // Manual timestamp update
                ]);

            return response()->json([
                'status'  => 'success',
                'message' => 'Scheme updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Update failed: ' . $e->getMessage()
            ]);
        }
    }
}
