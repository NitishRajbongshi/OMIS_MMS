<?php

namespace App\Http\Controllers\Master\Bridge;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeckTypeController extends Controller
{
   public function index()
    {
        $deckTypes = DB::table('public.asset_master_deck_types')->get();
        return view('master.bridges.deckTypes', compact('deckTypes'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('public.asset_master_deck_types')->insert([
                'deck_type_cd' => strtoupper($request->deck_type_cd),
                'deck_type_descr' => $request->deck_type_descr,
                'created_at' => now()
            ]);
            return response()->json(['status' => 'success', 'message' => 'Deck type added!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or database error']);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::table('public.asset_master_deck_types')
                ->where('deck_type_cd', $request->old_deck_type_cd)
                ->update([
                 //   'deck_type_cd' => strtoupper($request->deck_type_cd),
                    'deck_type_descr' => $request->deck_type_descr,
                    'updated_at' => now()
                ]);
            return response()->json(['status' => 'success', 'message' => 'Deck type updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}

