<?php

namespace App\Http\Controllers\Master\Construction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FaceWallTypeController extends Controller
{

    public function index()
    {
        $wallTypes = DB::table('public.asset_master_face_wall_types')->get();
        return view('master.construction.faceWallTypes', compact('wallTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_face_wall_types')->insert([
                'face_wall_type_cd' => strtoupper($request->face_wall_type_cd),
                'face_wall_type_descr' => $request->face_wall_type_descr
            ]);
            return response()->json(['status' => 'success', 'message' => 'Wall Type added successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_face_wall_types')
                ->where('face_wall_type_cd', $request->old_cd)
                ->update([
             //       'face_wall_type_cd' => strtoupper($request->face_wall_type_cd),
                    'face_wall_type_descr' => $request->face_wall_type_descr
                ]);
            return response()->json(['status' => 'success', 'message' => 'Wall Type updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed. Check for duplicate codes.']);
        }
    }
}
