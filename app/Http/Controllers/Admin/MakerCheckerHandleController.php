<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MakerCheckerHandleController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        DB::enableQueryLog();
        Log::info("Maker Checker handle controller");
    }

    public function index()
    {
        try {
            $subAssets = DB::table('asset_master_road_sub_assets')
                ->select('sub_asset_cd', 'sub_assets_descr', 'maker_checker_enabled')
                ->orderBy('sub_assets_descr', 'asc')
                ->get();

            return view('admin.makerCheckerControl', compact('subAssets'));
        } catch (QueryException $e) {
            Log::error("Database Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'query' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]);
            return response()->view('errors.generic', [], 500);
        } catch (Exception $e) {
            // Handles general errors
            Log::error("Unexpected Error: " . $e->getMessage(), [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->view('errors.generic', [], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'sub_asset_cd' => 'required|string|max:5',
                'maker_checker_enabled' => 'required|string|in:Y,N'
            ]);

            $updated = DB::table('asset_master_road_sub_assets')
                ->where('sub_asset_cd', $validated['sub_asset_cd'])
                ->update(['maker_checker_enabled' => $validated['maker_checker_enabled']]);
            if (!$updated) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Failed to update. Try again!'
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Action performed succefully.'
            ]);
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 'failed',
                'message' => 'Internal Server Error!'
            ]);
        }
    }
}
