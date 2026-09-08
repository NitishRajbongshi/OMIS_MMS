<?php

namespace App\Http\Controllers\Master\Projects;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BOQItemController extends Controller
{
    public function index()
    {
       $boqItems = DB::table('projects.prm_boq_items as boq')
            ->leftJoin('public.department_details as d', 'boq.dept_cd', '=', 'd.id')
            ->select('boq.*', 'd.department_name')
            ->get();

        $units = DB::table('projects.prm_item_units')->where('is_published', 'Y')->get();
        $departments = DB::table('public.department_details')->get();
        
        return view('master.projects.boqItems', compact('boqItems', 'units', 'departments'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('projects.prm_boq_items')->insert([
                'boq_item_name' => $request->boq_item_name,
                'unit_cd' => $request->unit_cd,
                'dept_cd' => $request->dept_cd,
                'is_published' => $request->is_published,
                'created_at' => Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'BOQ Item added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Database error: Please check constraints.']);
        }
    }

   
    public function update(Request $request, $id)
    {
       try {
            DB::table('projects.prm_boq_items')
                ->where('boq_item_id', $request->boq_item_id)
                ->update([
                    'boq_item_name' => $request->boq_item_name,
                    'unit_cd' => $request->unit_cd,
                    'dept_cd' => $request->dept_cd,
                    'is_published' => $request->is_published,
                    'updated_at' => Carbon::now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'BOQ Item updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
