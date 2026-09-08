<?php

namespace App\Http\Controllers\Road\Abstract;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CulvertAbstractController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
        DB::enableQueryLog();
        Log::info("CulvertAbstractController initialized");
    }

    public function getCulvertDetails(Request $request)
    {
        try {
            $rd_system_id = $request->rd_id;
            if (empty($rd_system_id)) {
                return response()->json([
                    'status' => 'failed',
                    'result' => "Please provide valid road id!"
                ]);
            }
            $cd_work_details = DB::table('asset_road_cdwork_details')
                ->select(
                    'asset_road_cdwork_details.*',
                    'asset_master_rd_cdworks_type.cdwoerk_descr',
                    'asset_master_road_condition.rd_condition_descr as cd_condition',
                    'asset_master_culvert_outlet_types.outlet_type_descr',
                    'asset_master_catch_pit_types.catch_pit_type_descr',
                    'asset_master_road_condition_cp.rd_condition_descr as cp_condition',
                    'asset_master_hume_pipe_specifications.hume_pipe_descr',
                    'asset_master_abutment_types.abutment_type_descr',
                    'asset_master_bearing_types.bearing_type_descr',
                    'asset_master_safety_apron_types.apron_type_descr',
                    'asset_master_construction_material_types.const_material_type_descr'
                )
                ->leftJoin('asset_master_rd_cdworks_type', 'asset_road_cdwork_details.culvert_type_cd', '=', 'asset_master_rd_cdworks_type.cdwork_cd')
                ->leftJoin('asset_master_road_condition', 'asset_road_cdwork_details.cdwork_condition', '=', 'asset_master_road_condition.rd_condition_cd')
                ->leftJoin('asset_master_culvert_outlet_types', 'asset_road_cdwork_details.outlet_type_cd', '=', 'asset_master_culvert_outlet_types.outlet_type_cd')
                ->leftJoin('asset_master_catch_pit_types', 'asset_road_cdwork_details.catch_pit_type_cd', '=', 'asset_master_catch_pit_types.catch_pit_type_cd')
                ->leftJoin('asset_master_road_condition AS asset_master_road_condition_cp', 'asset_road_cdwork_details.catch_pit_condition', '=', 'asset_master_road_condition_cp.rd_condition_cd')
                ->leftJoin('asset_master_hume_pipe_specifications', 'asset_road_cdwork_details.pipe_specification', '=', 'asset_master_hume_pipe_specifications.hume_pipe_cd')
                ->leftJoin('asset_master_abutment_types', 'asset_road_cdwork_details.abutment_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
                ->leftJoin('asset_master_bearing_types', 'asset_road_cdwork_details.bearing_type_cd', '=', 'asset_master_bearing_types.bearing_type_cd')
                ->leftJoin('asset_master_safety_apron_types', 'asset_road_cdwork_details.cdwork_safety_apron_type', '=', 'asset_master_safety_apron_types.apron_type_cd')
                ->leftJoin('asset_master_construction_material_types', 'asset_road_cdwork_details.const_material_type_cd', '=', 'asset_master_construction_material_types.const_material_type_cd')
                ->where('rd_system_id', '=', $rd_system_id)
                ->orderBy('updated_at', 'desc')
                ->get();
            if ($cd_work_details) {
                Log::info("Culvert details fetched successfully", [
                    'rd_system_id' => $rd_system_id,
                    'cd_work_details' => $cd_work_details
                ]);
                return response()->json([
                    'status' => 'success',
                    'result' => $cd_work_details
                ]);
            } else
                return response()->json([
                    'status' => 'failed',
                    'result' => "No culvert data found!!"
                ]);
        } catch (Exception $e) {
            Log::error("message: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'status' => 'failed',
                'result' => "Some Technical Issue Occured"
            ]);
        }
    }
}
