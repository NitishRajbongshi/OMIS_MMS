<?php

namespace App\Http\Controllers\MIS\CriticalityIndex;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CriticalityIndexMisController extends Controller
{

    public function searchCriticalityIndexRoads(Request $request)
    {
        try {
            if ($request->header('X-CSRF-TOKEN')) {
                DB::enableQueryLog();
                Log::info('MIS Road Controller: ');
                $category = $request->input('category');
                $type = $request->input('type');
                $zoneCd = $request->input('zone');
                $circleCd = $request->input('circle');
                $divisionCd = $request->input('division');
                $subDivisionCd = $request->input('subDivision');
                $baseQuery = DB::table('maintenance.master_asset_criticality_indexes as ci')
                    ->select(
                        'ci.asset_id',
                        'ci.criticality_index',
                        'rd.rd_system_id',
                        'rd.rd_number',
                        'rd.rd_name',
                        'rd.road_length',
                        'asset_master_road_category.rd_catg_descr',
                    )
                    ->leftJoin('asset_road_details as rd', 'rd.rd_system_id', '=', 'ci.asset_id')
                    ->leftJoin('asset_master_road_category', 'rd.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->where('rd.road_type', '=', 'SR')
                    ->orderBy('rd.created_at', 'desc');
                if ($category != 'null') {
                    $baseQuery->where('rd.rd_category_cd', $category);
                }
                if ($type != 'null') {
                    $baseQuery->where('rd.rd_type_cd', $type);
                }
                if ($zoneCd != 'null') {
                    $baseQuery->whereIn('rd.rd_system_id', function ($query) use ($zoneCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('zone_cd', $zoneCd);
                    });
                }
                if ($circleCd != 'null') {
                    $baseQuery->whereIn('rd.rd_system_id', function ($query) use ($circleCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('circle_cd', $circleCd);
                    });
                }
                if ($divisionCd != 'null') {
                    $baseQuery->whereIn('rd.rd_system_id', function ($query) use ($divisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('division_cd', $divisionCd);
                    });
                }
                if ($subDivisionCd != 'null') {
                    $baseQuery->whereIn('rd.rd_system_id', function ($query) use ($subDivisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('sub_division_cd', $subDivisionCd);
                    });
                }
                $roadDetails = $baseQuery->get();
                $query = DB::getQueryLog();
                Log::info($query);
                if ($roadDetails) {
                    if ($roadDetails->count() == 0) {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Data not available!',
                            'result' => $roadDetails
                        ]);
                    } else {
                        return response()->json([
                            'status' => 200,
                            'message' => 'Road data fetched successfully!',
                            'result' => $roadDetails
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 204,
                        'message' => 'Road details not available!',
                        'result' => null
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthorized request!',
                    'result' => null
                ]);
            }
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'Stack Trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'status' => 500,
                'message' => 'Internal Server error!',
                'result' => $e
            ]);
        }
    }
}
