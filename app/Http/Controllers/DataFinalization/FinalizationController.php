<?php

namespace App\Http\Controllers\DataFinalization;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FinalizationController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function finalizeRoad(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $roadID = $request->input('assetList');
                $status = DB::table('asset_road_details_draft')
                    ->where('sent_for_finalize', 'N')
                    ->whereIn('rd_system_id', $roadID)
                    ->update([
                        'sent_for_finalize' => 'Y',
                        'sent_for_finalize_on' => Carbon::now(),
                        'sent_for_finalize_by' => Auth::user()->id
                    ]);
                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Successfully data send for finalization!',
                        'result' => $status
                    ]);
                } else {
                    return response()->json([
                        'status' => 503,
                        'message' => 'Road Data not available!',
                        'result' => null
                    ]);
                }
                return $status;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    public function finalizeCDWork(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $cdWorkCode = $request->input('assetList');
                $status = DB::table('asset_road_cdwork_details_draft')
                    ->where('sent_for_finalize', 'N')
                    ->whereIn('rd_cdwork_cd', $cdWorkCode)
                    ->update([
                        'sent_for_finalize' => 'Y',
                        'sent_for_finalize_on' => now(),
                        'sent_for_finalize_by' => Auth::user()->id,
                    ]);
                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Successfully data send for finalization!',
                        'result' => $status
                    ]);
                } else {
                    return response()->json([
                        'status' => 503,
                        'message' => 'Culvert Data not available!',
                        'result' => null
                    ]);
                }
                return $status;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    public function finalizeBridge(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $bridgeCode = $request->input('assetList');
                $status = DB::table('asset_road_bridge_details_draft')
                    ->where('sent_for_finalize', 'N')
                    ->whereIn('rd_bridge_cd', $bridgeCode)
                    ->update([
                        'sent_for_finalize' => 'Y',
                        'sent_for_finalize_on' => now(),
                        'sent_for_finalize_by' => Auth::user()->id,
                    ]);
                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Successfully data send for finalization!',
                        'result' => $status
                    ]);
                } else {
                    return response()->json([
                        'status' => 503,
                        'message' => 'Bridge Data not available!',
                        'result' => null
                    ]);
                }
                return $status;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    public function finalizeProtectionWall(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $assetList = $request->input('assetList');
                $status = DB::table('asset_protection_wall_details_draft')
                    ->where('sent_for_finalize', 'N')
                    ->whereIn('protection_wall_cd', $assetList)
                    ->update([
                        'sent_for_finalize' => 'Y',
                        'sent_for_finalize_on' => now(),
                        'sent_for_finalize_by' => Auth::user()->id,
                    ]);
                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Successfully data send for finalization!',
                        'result' => $status
                    ]);
                } else {
                    return response()->json([
                        'status' => 503,
                        'message' => 'Protection Wall data not available!',
                        'result' => null
                    ]);
                }
                return $status;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    public function finalizeHabitation(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $assetList = $request->input('assetList');
                $status = DB::table('asset_road_habitation_details_draft')
                    ->where('sent_for_finalize', 'N')
                    ->whereIn('habitation_cd', $assetList)
                    ->update([
                        'sent_for_finalize' => 'Y',
                        'sent_for_finalize_on' => now(),
                        'sent_for_finalize_by' => Auth::user()->id,
                    ]);
                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Successfully data send for finalization!',
                        'result' => $status
                    ]);
                } else {
                    return response()->json([
                        'status' => 503,
                        'message' => 'Habitation Data not available!',
                        'result' => null
                    ]);
                }
                return $status;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    public function finalizePCI(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $assetList = $request->input('assetList');
                $status = DB::table('asset_road_pavement_condition_indexes_draft')
                    ->where('sent_for_finalize', 'N')
                    ->whereIn('pci_section_cd', $assetList)
                    ->update([
                        'sent_for_finalize' => 'Y',
                        'sent_for_finalize_on' => now(),
                        'sent_for_finalize_by' => Auth::user()->id,
                    ]);
                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Successfully data send for finalization!',
                        'result' => $status
                    ]);
                } else {
                    return response()->json([
                        'status' => 503,
                        'message' => 'PCI Data not available!',
                        'result' => null
                    ]);
                }
                return $status;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    public function finalizeSurfaceType(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $assetList = $request->input('assetList');
                $status = DB::table('asset_road_surface_type_details_draft')
                    ->where('sent_for_finalize', 'N')
                    ->whereIn('rd_surface_cd', $assetList)
                    ->update([
                        'sent_for_finalize' => 'Y',
                        'sent_for_finalize_on' => now(),
                        'sent_for_finalize_by' => Auth::user()->id,
                    ]);
                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Successfully data send for finalization!',
                        'result' => $status
                    ]);
                } else {
                    return response()->json([
                        'status' => 503,
                        'message' => 'SurfaceType Data not available!',
                        'result' => null
                    ]);
                }
                return $status;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }

    public function finalizeBuilding(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                $housingCode = $request->input('assetList');
                $status = DB::table('buildings.asset_building_details_draft')
                    ->where('sent_for_finalize', 'N')
                    ->whereIn('building_system_cd', $housingCode)
                    ->update([
                        'sent_for_finalize' => 'Y',
                        'sent_for_finalize_on' => Carbon::now(),
                        'sent_for_finalize_by' => Auth::user()->id
                    ]);
                if ($status) {
                    return response()->json([
                        'status' => 200,
                        'message' => 'Successfully data send for finalization!',
                        'result' => $status
                    ]);
                } else {
                    return response()->json([
                        'status' => 503,
                        'message' => 'Housing Data not available!',
                        'result' => null
                    ]);
                }
                return $status;
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => null
            ]);
        }
    }
}
