<?php

namespace App\Http\Controllers\Master\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SchemeFundMappingController extends Controller
{
    public function index()
    {
        // Dropdown Data
        $schemes = DB::table('projects.prm_scheme_details')
            ->where('is_published', 'Y')
            ->get();

        $agencies = DB::table('projects.prm_funding_agency_details')
            ->where('is_published', 'Y')
            ->get();

        // Mapping List
        $mappings = DB::table('projects.prm_scheme_funding_mapping as m')
            ->join('projects.prm_scheme_details as s', 'm.scheme_id', '=', 's.scheme_id')
            ->join('projects.prm_funding_agency_details as a', 'm.funding_agency_id', '=', 'a.funding_agency_id')
            ->select(
                'm.*',
                's.scheme_name',
                's.scheme_code',
                'a.agency_name'
            )
            ->get();

        return view(
            'master.projects.schemeFundingMapping',
            compact('schemes', 'agencies', 'mappings')
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scheme_id' => 'required',
            'funding_agency_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {

                    $exists = DB::table('projects.prm_scheme_funding_mapping')
                        ->where('scheme_id', $request->scheme_id)
                        ->where('funding_agency_id', $value)
                        ->exists();

                    if ($exists) {
                        $fail('This agency is already mapped to this scheme.');
                    }
                },
            ],
            'funding_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        try {

            DB::table('projects.prm_scheme_funding_mapping')->insert([
                'scheme_id' => $request->scheme_id,
                'funding_agency_id' => $request->funding_agency_id,
                'funding_percentage' => $request->funding_percentage,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Mapping created successfully!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'scheme_id' => 'required',
            'funding_agency_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request, $id) {

                    $exists = DB::table('projects.prm_scheme_funding_mapping')
                        ->where('scheme_id', $request->scheme_id)
                        ->where('funding_agency_id', $value)
                        ->where('id', '!=', $id)
                        ->exists();

                    if ($exists) {
                        $fail('This agency is already mapped to this scheme.');
                    }
                },
            ],
            'funding_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ]);
        }

        try {

            DB::table('projects.prm_scheme_funding_mapping')
                ->where('id', $id)
                ->update([
                    'scheme_id' => $request->scheme_id,
                    'funding_agency_id' => $request->funding_agency_id,
                    'funding_percentage' => $request->funding_percentage,
                ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Updated successfully!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}