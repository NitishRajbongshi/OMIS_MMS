<?php

namespace App\Http\Controllers\Master\Projects;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemOfWorkMasterController extends Controller
{
    public function index()
    {
        $items = DB::table('projects.prm_item_of_work as iw')
            ->leftJoin('public.department_details as d', 'iw.dept_cd', '=', 'd.id')
            ->select('iw.*', 'd.department_name')
            ->get();

        $units = DB::table('projects.prm_item_units')->get();
        $departments = DB::table('public.department_details')->get();

        return view('master.projects.prmItemOfWork', compact('items','units','departments'));
    }

    public function store(Request $request)
    {
		Log::info($request->all());						   
        try {
            DB::table('projects.prm_item_of_work')->insert([
                'item_name' => $request->item_name,
                'dept_cd' => $request->dept_cd,
                'unit_cd' => $request->unit_cd,
                'is_published' => $request->is_published,
                'created_at' => Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Item of Work added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to save record.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('projects.prm_item_of_work')
                ->where('item_cd', $request->item_cd)
                ->update([
                    'item_name' => $request->item_name,
                    'dept_cd' => $request->dept_cd,
                    'unit_cd' => $request->unit_cd,
                    'is_published' => $request->is_published,
                    'updated_at' => Carbon::now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Item updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
