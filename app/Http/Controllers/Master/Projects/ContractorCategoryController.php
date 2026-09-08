<?php

namespace App\Http\Controllers\Master\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractorCategoryController extends Controller
{
    public function index()
    {
        $categories = DB::table('projects.prm_contractor_categories')
            ->orderBy('category_cd')
            ->get();
        return view('master.projects.contractorCategory', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('projects.prm_contractor_categories')->insert([
                'category_cd'    => $request->category_cd,
                'category_descr' => $request->category_descr,
                'is_published'   => $request->has('is_published') ? 'Y' : 'N',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Category saved!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Duplicate code or Database error']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('projects.prm_contractor_categories')
                ->where('category_cd', $request->category_cd)
                ->update([
                    'category_descr' => $request->category_descr,
                    'is_published'   => $request->has('is_published') ? 'Y' : 'N',
                    'updated_at'     => now(),
                ]);
            return response()->json(['status' => 'success', 'message' => 'Category updated!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Update failed']);
        }
    }
}
