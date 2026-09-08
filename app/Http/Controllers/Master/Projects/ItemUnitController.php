<?php

namespace App\Http\Controllers\Master\Projects;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemUnitController extends Controller
{
    public function index()
    {
        $units = DB::table('projects.prm_item_units')->get();
        return view('master.projects.prmItemUnits', compact('units'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('projects.prm_item_units')->insert([
                'unit_cd' => strtoupper($request->unit_cd),
                'unit_descr' => $request->unit_descr,
                'is_published' => $request->is_published ?? 'Y',
                'created_at' => Carbon::now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Measurement unit added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Failed to save unit. Code might already exist.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('projects.prm_item_units')
                ->where('unit_cd', $request->old_cd)
                ->update([
                    //'unit_cd' => strtoupper($request->unit_cd),
                    'unit_descr' => $request->unit_descr,
                    'is_published' => $request->is_published,
                    'updated_at' => Carbon::now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Unit updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed.']);
        }
    }
}
