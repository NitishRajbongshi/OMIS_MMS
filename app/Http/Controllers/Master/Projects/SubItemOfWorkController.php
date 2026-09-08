<?php

namespace App\Http\Controllers\Master\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubItemOfWorkController extends Controller
{
   public function index()
    {
        $subItems = DB::table('projects.prm_item_sub_item_of_work as s')
            ->leftJoin('projects.prm_item_of_work as i', 's.item_cd', '=', 'i.item_cd')
            ->leftJoin('public.department_details as d', 's.dept_cd', '=', 'd.id')
            ->select('s.*', 'i.item_name', 'd.department_name')
            ->orderBy('s.sub_item_cd', 'desc')
            ->get();

        $departments = DB::table('public.department_details')->get();
        $items = DB::table('projects.prm_item_of_work')->get();

        return view('master.projects.prmSubItemOfWork', compact('subItems', 'departments', 'items'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('projects.prm_item_sub_item_of_work')->insert([
                'sub_item_name' => $request->sub_item_name,
                'item_cd'       => $request->item_cd,
                'dept_cd'       => $request->dept_cd,
                'is_published'  => $request->is_published,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Sub-item added!']);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Sub-item added failed']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('projects.prm_item_sub_item_of_work')
                ->where('sub_item_cd', $request->sub_item_cd)
                ->update([
                    'sub_item_name' => $request->sub_item_name,
                    'item_cd'       => $request->item_cd,
                    'dept_cd'       => $request->dept_cd,
                    'is_published'  => $request->is_published,
                    'updated_at'    => now(),
                ]);
            return response()->json(['status' => 'success', 'message' => 'Sub-item updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
