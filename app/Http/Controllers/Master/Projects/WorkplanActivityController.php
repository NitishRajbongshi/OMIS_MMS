<?php

namespace App\Http\Controllers\Master\Projects;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkplanActivityController extends Controller
{
    public function index()
    {
        $activities = DB::table('projects.prm_workplan_activities as wa')
            ->leftJoin('public.department_details as d', 'wa.dept_cd', '=', 'd.id')
            ->select('wa.*', 'd.department_name')
            ->get();

        $departments = DB::table('public.department_details')->get();
        
        return view('master.projects.workPlanActivities', compact('activities', 'departments'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('projects.prm_workplan_activities')->insert([
                'wp_name' => $request->wp_name,
                'dept_cd' => $request->dept_cd,
                'is_published' => $request->is_published,
                'created_at' => Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Activity created!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Database error.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('projects.prm_workplan_activities')
                ->where('wp_cd', $request->wp_cd)
                ->update([
                    'wp_name' => $request->wp_name,
                    'dept_cd' => $request->dept_cd,
                    'is_published' => $request->is_published,
                    'updated_at' => Carbon::now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Activity updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
