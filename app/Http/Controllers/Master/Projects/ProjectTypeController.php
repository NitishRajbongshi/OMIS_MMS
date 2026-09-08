<?php

namespace App\Http\Controllers\Master\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectTypeController extends Controller
{
    public function index()
    {
        $types = DB::table('projects.prm_project_types')
            ->orderBy('proj_type_cd', 'asc')
            ->get();
            
        return view('master.projects.projectTypes', compact('types'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('projects.prm_project_types')->insert([
                'proj_type_cd'    => strtoupper($request->proj_type_cd),
                'proj_type_descr' => $request->proj_type_descr,
                'is_published'   => $request->has('is_published') ? 'Y' : 'N',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Project type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error: Duplicate code or DB issue']);
        }
    }

   public function update(Request $request, $id)
    {
        try {
            // We use 'old_proj_type_cd' to find the record, 
            // and 'proj_type_cd' to set the new value.
            DB::table('projects.prm_project_types')
                ->where('proj_type_cd', $request->old_proj_type_cd)
                ->update([
                    //'proj_type_cd'    => strtoupper($request->proj_type_cd), // Update the Primary Key
                    'proj_type_descr' => $request->proj_type_descr,
                    'is_published'   => $request->has('is_published') ? 'Y' : 'N',
                    'updated_at'     => now(),
                ]);

            return response()->json(['status' => 'success', 'message' => 'Project type updated!']);
        } catch (\Exception $e) {
            // Catch errors like trying to update to a code that already exists
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
