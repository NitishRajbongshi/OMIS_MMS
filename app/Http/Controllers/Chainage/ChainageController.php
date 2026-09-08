<?php

namespace App\Http\Controllers\Chainage;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Road\AssetRoadDetail;
use App\Models\AssetRoadCbrValueDetail;

class ChainageController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }

    public function index(Request $request)
    {
        $systemId = $request->id;
        $roadDetails = AssetRoadDetail::find($systemId);
        session(['system_id' => $systemId]);
        session(['road_name' => $roadDetails->rd_name]);
        session(['road_number' => $roadDetails->rd_number]);
        session(['road_length' => $roadDetails->road_length]);
        return redirect()->route('roadChainage');
    }

    public function show(Request $request)
    {
        try {
            DB::enableQueryLog();
            Log::info('Chainage controller: ');
            $road_system_id = session('system_id');

            $CbrDetails = DB::table('asset_road_chainage_mappings')
                ->select('asset_road_chainage_mappings.rd_system_id', 'asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to', 'asset_road_chainage_mappings.zone_cd', 'asset_road_chainage_mappings.circle_cd', 'asset_road_chainage_mappings.division_cd', 'asset_road_chainage_mappings.sub_division_cd', 'asset_road_chainage_mappings.remaining_chainage_length', 'asset_master_zones.zone_name', 'asset_master_circles.circle_name', 'asset_master_divisions.division_name', 'asset_master_sub_divisions.sub_div_name')
                ->leftJoin('asset_master_zones', 'asset_road_chainage_mappings.zone_cd', '=', 'asset_master_zones.zone_cd')
                ->leftJoin('asset_master_circles', 'asset_road_chainage_mappings.circle_cd', '=', 'asset_master_circles.circle_cd')
                ->leftJoin('asset_master_divisions', 'asset_road_chainage_mappings.division_cd', '=', 'asset_master_divisions.division_cd')
                ->leftJoin('asset_master_sub_divisions', 'asset_road_chainage_mappings.sub_division_cd', '=', 'asset_master_sub_divisions.sub_div_cd')
                ->where('asset_road_chainage_mappings.rd_system_id', '=', $road_system_id)
                ->orderBy('asset_road_chainage_mappings.updated_at', 'asc')
                ->get();

            $roadChainage = DB::table('asset_road_chainage_mappings')
                ->select('asset_road_chainage_mappings.chainage_from', 'asset_road_chainage_mappings.chainage_to')
                ->where('rd_system_id', '=', $road_system_id)
                ->get()->first();
            $totalSegmentValue = AssetRoadCbrValueDetail::where('rd_system_id', $road_system_id)->latest()->first();

            if (($totalSegmentValue != null) && ($totalSegmentValue["remaining_length"] == '0.000')) {
                session(['warning' => 'Segmentation is completed for this road.']);
            }
            $query = DB::getQueryLog();
            Log::info($query);
            return view('road.chainage.get-road-chainage', compact(
                'CbrDetails',
                'roadChainage',
                'totalSegmentValue',
            ));
        } catch (Exception $e) {
            Log::error("Error: ");
            Log::error($e->getMessage());
            return view('error');
        }
    }
}
