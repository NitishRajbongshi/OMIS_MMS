<?php

namespace App\Http\Controllers\Master\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;							   

class FundingAgnecies extends Controller
{
   /**
     * List Agencies
     */
    public function index()
    {
        $agencies = DB::table('projects.prm_funding_agency_details')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('master.projects.fundingAgencies', compact('agencies'));
    }

    /**
     * Store Agency
     */
    public function store(Request $request)
    {
		$request->merge([
            'agency_code' => strtoupper(trim($request->agency_code))
        ]);

        $validator = Validator::make(
            $request->all(),
            [
                'agency_code' => [
                    'required',
                    'max:50',
                    Rule::unique('pgsql_pms.prm_funding_agency_details', 'agency_code')
                ],

                'agency_name' => 'required|max:100',
                'contact_email' => 'nullable|email|max:150',
            ],
            [
                'agency_code.unique' => 'This agency code already exists.',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ]);

        }

        try {
            DB::table('projects.prm_funding_agency_details')->insert([
                'agency_name'    => $request->agency_name,
                'agency_code'    => $request->agency_code,
                'contact_person' => $request->contact_person,
                'contact_email'  => $request->contact_email,
                'contact_phone'  => $request->contact_phone,
                'address'        => $request->address,
                'is_published'   => $request->is_published,
                'created_at'     => Carbon::now(),
                'updated_at'     => Carbon::now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Agency added successfully!'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong.'
            ]);

        }
    }
    /**
     * Update Agency
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
           // 'agency_code' => 'required|max:50|unique:pgsql_pms.prm_funding_agency_details,agency_code,' . $id . ',funding_agency_id',
            'agency_name' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }

        try {
            DB::table('projects.prm_funding_agency_details')
                ->where('funding_agency_id', $id)
                ->update([
                    'agency_name'    => $request->agency_name,
                    'contact_person' => $request->contact_person,
                    'contact_email'  => $request->contact_email,
                    'contact_phone'  => $request->contact_phone,
                    'address'        => $request->address,
                    'is_published'   => $request->is_published,
                    'updated_at'     => Carbon::now(),
                ]);

            return response()->json(['status' => 'success', 'message' => 'Agency updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed: ' . $e->getMessage()]);
        }
    }
}
