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
                $category = $request->input('category');
                $type = $request->input('type');
                $owner = $request->input('owner');
                $zoneCd = $request->input('zone');
                $circleCd = $request->input('circle');
                $divisionCd = $request->input('division');
                $subDivisionCd = $request->input('subDivision');
                $baseQuery = DB::table('asset_road_details')
                    ->select(
                        'asset_road_details.rd_system_id',
                        'asset_road_details.rd_number',
                        'asset_road_details.rd_name',
                        'asset_road_details.road_length',
                        'asset_road_details.district_name',
                        'asset_road_details.division_name',
                        'asset_road_details.rd_category_cd',
                        'asset_road_details.lat',
                        'asset_road_details.lng',
                        'asset_master_road_category.rd_catg_descr',
                        'asset_master_rd_type.rd_type_descr',
                        'asset_master_road_owner.owner_name'
                    )
                    ->leftJoin('asset_master_road_category', 'asset_road_details.rd_category_cd', '=', 'asset_master_road_category.rd_catg_cd')
                    ->leftJoin('asset_master_rd_type', 'asset_road_details.rd_type_cd', '=', 'asset_master_rd_type.rd_type_cd')
                    ->leftJoin('asset_master_road_owner', 'asset_road_details.rd_owner_cd', '=', 'asset_master_road_owner.owner_cd')
                    ->where('asset_road_details.road_type', '=', 'SR')
                    ->orderBy('created_at', 'desc');
                if ($category != 'null') {
                    $baseQuery->where('asset_road_details.rd_category_cd', $category);
                }
                if ($type != 'null') {
                    $baseQuery->where('asset_road_details.rd_type_cd', $type);
                }
                // if ($owner != 'null') {
                //     $baseQuery->where('asset_road_details.rd_owner_cd', $owner);
                // }
                if ($zoneCd != 'null') {
                    $baseQuery->whereIn('asset_road_details.rd_system_id', function ($query) use ($zoneCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('zone_cd', $zoneCd);
                    });
                }
                if ($circleCd != 'null') {
                    $baseQuery->whereIn('asset_road_details.rd_system_id', function ($query) use ($circleCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('circle_cd', $circleCd);
                    });
                }
                if ($divisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_details.rd_system_id', function ($query) use ($divisionCd) {
                        $query->select(DB::raw('distinct(arcm.rd_system_id)'))
                            ->from('asset_road_chainage_mappings as arcm')
                            ->where('division_cd', $divisionCd);
                    });
                }
                if ($subDivisionCd != 'null') {
                    $baseQuery->whereIn('asset_road_details.rd_system_id', function ($query) use ($subDivisionCd) {
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
