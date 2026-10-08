<?php

namespace App\Services\Maintenance;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoadSubAssetService
{
    /**
     * Fetch sub-assets for a road based on sub_asset_cd.
     */
    public function getSubAssets(string $roadId, string $subAssetCd)
    {
        $subAsset = (int)$subAssetCd;
        return match ($subAsset) {

            0 => $this->getCulvertDetails($roadId),
            1 => $this->getBridgeDetails($roadId),

            default => collect(),
        };
    }

    /**
     * Get culvert details for a specific road.
     */
    private function getCulvertDetails(string $roadId)
    {
        return DB::table('asset_road_cdwork_details')
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
                'asset_master_construction_material_types.const_material_type_descr',
            )
            ->leftJoin('asset_master_rd_cdworks_type', 'asset_road_cdwork_details.culvert_type_cd', '=', 'asset_master_rd_cdworks_type.cdwork_cd')
            ->leftJoin('asset_master_road_condition', 'asset_road_cdwork_details.cdwork_condition', '=', 'asset_master_road_condition.rd_condition_cd')
            ->leftJoin('asset_master_culvert_outlet_types', 'asset_road_cdwork_details.outlet_type_cd', '=', 'asset_master_culvert_outlet_types.outlet_type_cd')
            ->leftJoin('asset_master_catch_pit_types', 'asset_road_cdwork_details.catch_pit_type_cd', '=', 'asset_master_catch_pit_types.catch_pit_type_cd')
            ->leftJoin('asset_master_road_condition AS asset_master_road_condition_cp', 'asset_road_cdwork_details.catch_pit_condition', '=', 'asset_master_road_condition_cp.rd_condition_cd')
            ->leftJoin('asset_master_hume_pipe_specifications', 'asset_road_cdwork_details.pipe_specification', '=', 'asset_master_hume_pipe_specifications.hume_pipe_cd')
            ->leftJoin('asset_master_abutment_types', 'asset_road_cdwork_details.abutment_type_cd', '=', 'asset_master_abutment_types.abutment_type_cd')
            ->leftJoin('asset_master_safety_apron_types', 'asset_road_cdwork_details.cdwork_safety_apron_type', '=', 'asset_master_safety_apron_types.apron_type_cd')
            ->leftJoin('asset_master_construction_material_types', 'asset_road_cdwork_details.const_material_type_cd', '=', 'asset_master_construction_material_types.const_material_type_cd')
            ->leftJoin('asset_master_bearing_types', 'asset_road_cdwork_details.bearing_type_cd', '=', 'asset_master_bearing_types.bearing_type_cd')
            ->where('asset_road_cdwork_details.rd_system_id', $roadId)
            ->orderBy('asset_road_cdwork_details.updated_at', 'desc')
            ->get();
    }

    /**
     * Get bridge details for a specific road.
     */
    private function getBridgeDetails(string $roadId)
    {
        return DB::table('public.asset_road_bridge_details')
            ->select(
                'asset_road_bridge_details.*',
                'asset_master_bridge_type.bridge_type_descr',
                'asset_master_construction_types.construction_type_descr',
                'asset_master_abutment_types.abutment_type_descr',
                'asset_master_super_structure_types.st_type_descr',
                'asset_master_handrail_types.hand_rail_type_descr',
                'asset_master_deck_types.deck_type_descr',
                'asset_master_expansion_joints.expn_joint_descr',
                'asset_master_road_condition.rd_condition_descr',
            )
            ->leftJoin(
                'public.asset_master_bridge_type',
                'asset_road_bridge_details.bridge_type_cd',
                '=',
                'asset_master_bridge_type.bridge_type_cd'
            )
            ->leftJoin(
                'public.asset_master_construction_types',
                'asset_road_bridge_details.construction_type_cd',
                '=',
                'asset_master_construction_types.construction_type_cd'
            )
            ->leftJoin(
                'public.asset_master_abutment_types',
                'asset_road_bridge_details.abutment_type_cd',
                '=',
                'asset_master_abutment_types.abutment_type_cd'
            )
            ->leftJoin(
                'public.asset_master_super_structure_types',
                'asset_road_bridge_details.super_structure_type_cd',
                '=',
                'asset_master_super_structure_types.st_type_cd'
            )
            ->leftJoin(
                'public.asset_master_handrail_types',
                'asset_road_bridge_details.handrail_type_cd',
                '=',
                'asset_master_handrail_types.hand_rail_type_cd'
            )
            ->leftJoin(
                'public.asset_master_deck_types',
                'asset_road_bridge_details.deck_type_cd',
                '=',
                'asset_master_deck_types.deck_type_cd'
            )
            ->leftJoin(
                'public.asset_master_expansion_joints',
                'asset_road_bridge_details.expansion_join_cd',
                '=',
                'asset_master_expansion_joints.expn_joint_cd'
            )
            ->leftJoin(
                'public.asset_master_road_condition',
                'asset_road_bridge_details.bridge_condition',
                '=',
                'asset_master_road_condition.rd_condition_cd'
            )
            ->where(
                'asset_road_bridge_details.rd_system_id',
                $roadId
            )
            ->orderBy(
                'asset_road_bridge_details.updated_at',
                'desc'
            )
            ->get();
    }
}
