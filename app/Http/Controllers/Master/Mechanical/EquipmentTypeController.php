<?php

namespace App\Http\Controllers\Master\Mechanical;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipmentTypeController extends Controller
{
    public function index()
    {
        $equipmentTypes = DB::table('mechanicals.asset_master_euipment_types')->get();
        return view('master.mechanical.equipmentTypes', compact('equipmentTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('mechanicals.asset_master_euipment_types')->insert([
                'equipment_type_cd' => strtoupper($request->equipment_type_cd),
                'equipment_type_descr' => $request->equipment_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Equipment type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('mechanicals.asset_master_euipment_types')
                ->where('equipment_type_cd', $request->old_cd)
                ->update([
             //       'equipment_type_cd' => strtoupper($request->equipment_type_cd),
                    'equipment_type_descr' => $request->equipment_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Equipment type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
