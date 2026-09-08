<?php

namespace App\Http\Controllers\Master\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StreamTypeController extends Controller
{
    public function index()
    {
        $streamTypes = DB::table('public.asset_master_head_walls_stream_types')->get();
        return view('master.bridges.headWallStreamTypes', compact('streamTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_head_walls_stream_types')->insert([
                'stream_type_cd' => strtoupper($request->stream_type_cd),
                'stream_descr' => $request->stream_descr,
                'created_at' => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Stream type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_head_walls_stream_types')
                ->where('stream_type_cd', $request->old_stream_type_cd)
                ->update([
                 //   'stream_type_cd' => strtoupper($request->stream_type_cd),
                    'stream_descr' => $request->stream_descr,
                    'updated_at' => now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Stream type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
