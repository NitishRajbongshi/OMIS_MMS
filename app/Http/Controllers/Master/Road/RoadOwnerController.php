<?php

namespace App\Http\Controllers\Master\Road;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoadOwnerController extends Controller
{
   public function index()
    {
        $owners = DB::table('public.asset_master_road_owner')->get();
        return view('master.road.roadOwner', compact('owners'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_road_owner')->insert([
                'owner_cd' => strtoupper($request->owner_cd),
                'owner_name' => $request->owner_name,
                'owner_short_code' => strtoupper($request->owner_short_code)
            ]);
            return response()->json(['status' => 'success', 'message' => 'Road owner added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('public.asset_master_road_owner')
                ->where('owner_cd', $request->old_owner_cd)
                ->update([
              //      'owner_cd' => strtoupper($request->owner_cd),
                    'owner_name' => $request->owner_name,
                    'owner_short_code' => strtoupper($request->owner_short_code)
                ]);
            return response()->json(['status' => 'success', 'message' => 'Road owner updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
