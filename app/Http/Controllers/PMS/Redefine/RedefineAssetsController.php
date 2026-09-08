<?php

namespace App\Http\Controllers\PMS\Redefine;

use App\Http\Controllers\Controller;
use App\Models\Road\AssetRoadDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\AssetMasterLgdDistrict;
use App\Models\AssetMasterRdType;
use App\Models\AssetMasterRoadOwner;
use App\Models\AssetMasterRoadCategory;


class RedefineAssetsController extends Controller
{
    public function redefineAsset(Request $request)
    {
        $assetPlanId = $request->asset_plan_id;
        $projectCd = $request->project_cd;
        $assetCd = $request->asset_cd;
        $parentCd = $request->parent_cd;
        $typeKey = $request->typeKey;
        $dept_cd = $request->dept_cd;
        $copyStatus = false;
        $copyMessage = null;
        $copiedRows = 0;
        $currentTime = now();
        $userId = Auth::user()->id;
        try {
            DB::beginTransaction();
            if ($dept_cd == 14) {
                switch ($typeKey) {
                    case '10':
                        $copiedRows = DB::table('public.asset_road_details_draft')->insertUsing([
                            'rd_system_id',
                            'rd_category_cd',
                            'rd_number',
                            'rd_name',
                            'rd_type_cd',
                            'road_length',
                            'rd_owner_cd',
                            'road_created_at_office_type',
                            'road_created_at_office_cd',
                            'road_type',
                            'district_name',
                            'block_name',
                            'lng',
                            'lat',
                            'division_name',
                            'division_cd',
                            'block_cd',
                            'district_cd',
                            'created_at',
                            'updated_at',
                            'created_by',
                            'updated_by',
                            'asset_plan_id',
                            'sent_for_finalize'
                        ], function ($query) use ($assetCd, $assetPlanId, $currentTime, $userId) {
                            $query->from('public.asset_road_details')
                                ->where('rd_system_id', $assetCd)
                                ->whereNotExists(function ($exists) use ($assetCd) {
                                    $exists->select(DB::raw(1))
                                        ->from('public.asset_road_details_draft')
                                        ->where('rd_system_id', $assetCd);
                                })
                                ->select(
                                    'rd_system_id',
                                    'rd_category_cd',
                                    'rd_number',
                                    'rd_name',
                                    'rd_type_cd',
                                    'road_length',
                                    'rd_owner_cd',
                                    'road_created_at_office_type',
                                    'road_created_at_office_cd',
                                    'road_type',
                                    'district_name',
                                    'block_name',
                                    'lng',
                                    'lat',
                                    'division_name',
                                    'division_cd',
                                    'block_cd',
                                    'district_cd',
                                    'created_at',
                                    'updated_at',
                                    'created_by',
                                    'updated_by',
                                    DB::raw("'" . $assetPlanId . "' as asset_plan_id"),
                                    DB::raw("'N' as sent_for_finalize")
                                );
                        });

                        $roadDraftDetails = DB::table('public.asset_road_details_draft')
                            ->where('rd_system_id', $assetCd)
                            ->first();
                        $districts = AssetMasterLgdDistrict::all();
                        $roadTypes = AssetMasterRdType::all();
                        $roadOwners = AssetMasterRoadOwner::all();
                        $roadCategories = AssetMasterRoadCategory::all();
                        break;
                    case '0':
                        $copiedRows = DB::table('public.asset_road_cdwork_details_draft')->insertUsing([
                            'rd_cdwork_cd',
                            'rd_system_id',
                            'culvert_no',
                            'chainage',
                            'culvert_type_cd',
                            'cussion',
                            'cdwork_outlet',
                            'cdwork_no_of_vents',
                            'cdwork_thickness_side_wall',
                            'cdwork_thickness_top_slab',
                            'cdwork_thickness_bottom_slab',
                            'cdwork_has_wing_wall',
                            'cdwork_condition',
                            'discharge',
                            'year_of_construction',
                            'year_of_rehabilitation',
                            'span',
                            'created_at_office_cd',
                            'no_of_rows',
                            'pipe_diameter',
                            'culvert_width',
                            'pipe_specification',
                            'length_span',
                            'no_of_wing_wall',
                            'width_each_cell',
                            'heigth_each_cell',
                            'slab_thickness',
                            'height_of_earth_cushion',
                            'slab_length',
                            'slab_width',
                            'no_of_cell',
                            'culvert_location',
                            'outlet_type_cd',
                            'catch_pit_availability',
                            'catch_pit_type_cd',
                            'catch_pit_width',
                            'catch_pit_condition',
                            'cdwork_remark',
                            'cdwork_has_head_wall',
                            'const_material_type_cd',
                            'abutment_type_cd',
                            'abutment_height',
                            'bearing_type_cd',
                            'cdwork_has_safety_apron',
                            'cdwork_safety_apron_type',
                            'cdwork_safety_apron_outlet',
                            'cdwork_safety_apron_width',
                            'cdwork_safety_apron_length',
                            'cdwork_safety_apron_slab_thickness',
                            'cdwork_safety_apron_hand_rail_type',
                            'catch_pit_heigth',
                            'catch_pit_breadth',
                            'catch_pit_thickness',
                            'sent_for_finalize',
                            'asset_plan_id',
                            'created_at',
                            'updated_at',
                            'updated_by',
                            'created_by'
                        ], function ($query) use ($assetCd, $assetPlanId, $currentTime, $userId) {
                            $query->from('public.asset_road_cdwork_details')
                                ->where('rd_cdwork_cd', $assetCd)
                                ->whereNotExists(function ($exists) use ($assetCd) {
                                    $exists->select(DB::raw(1))
                                        ->from('public.asset_road_cdwork_details_draft')
                                        ->where('rd_cdwork_cd', $assetCd);
                                })
                                ->select(
                                    'rd_cdwork_cd',
                                    'rd_system_id',
                                    'culvert_no',
                                    'chainage',
                                    'culvert_type_cd',
                                    'cussion',
                                    'cdwork_outlet',
                                    'cdwork_no_of_vents',
                                    'cdwork_thickness_side_wall',
                                    'cdwork_thickness_top_slab',
                                    'cdwork_thickness_bottom_slab',
                                    'cdwork_has_wing_wall',
                                    'cdwork_condition',
                                    'discharge',
                                    'year_of_construction',
                                    'year_of_rehabilitation',
                                    'span',
                                    'created_at_office_cd',
                                    'no_of_rows',
                                    'pipe_diameter',
                                    'culvert_width',
                                    'pipe_specification',
                                    'length_span',
                                    'no_of_wing_wall',
                                    'width_each_cell',
                                    'heigth_each_cell',
                                    'slab_thickness',
                                    'height_of_earth_cushion',
                                    'slab_length',
                                    'slab_width',
                                    'no_of_cell',
                                    'culvert_location',
                                    'outlet_type_cd',
                                    'catch_pit_availability',
                                    'catch_pit_type_cd',
                                    'catch_pit_width',
                                    'catch_pit_condition',
                                    'cdwork_remark',
                                    'cdwork_has_head_wall',
                                    'const_material_type_cd',
                                    'abutment_type_cd',
                                    'abutment_height',
                                    'bearing_type_cd',
                                    'cdwork_has_safety_apron',
                                    'cdwork_safety_apron_type',
                                    'cdwork_safety_apron_outlet',
                                    'cdwork_safety_apron_width',
                                    'cdwork_safety_apron_length',
                                    'cdwork_safety_apron_slab_thickness',
                                    'cdwork_safety_apron_hand_rail_type',
                                    'catch_pit_heigth',
                                    'catch_pit_breadth',
                                    'catch_pit_thickness',
                                    DB::raw("'N' as sent_for_finalize"),
                                    DB::raw("'" . $assetPlanId . "' as asset_plan_id"),
                                    'created_at',
                                    'updated_at',
                                    'updated_by',
                                    'created_by'
                                );
                        });

                        if ($copiedRows == 0 && DB::table('public.asset_road_cdwork_details_draft')->where('rd_cdwork_cd', $assetCd)->exists()) {
                            Log::warning('Duplicate CDWORK details found in draft while redefining asset.', [
                                'asset_plan_id' => $assetPlanId,
                                'project_cd' => $projectCd,
                                'asset_cd' => $assetCd,
                                'parent_cd' => $parentCd,
                                'type_key' => $typeKey,
                            ]);
                        }

                        Log::info('Copied CDWORK details to draft for redefine asset.', [
                            'asset_plan_id' => $assetPlanId,
                            'project_cd' => $projectCd,
                            'asset_cd' => $assetCd,
                            'parent_cd' => $parentCd,
                            'type_key' => $typeKey,
                            'copied_rows' => $copiedRows,
                        ]);

                        break;
                    case '1':
                        $copiedRows = DB::table('public.asset_road_bridge_details_draft')->insertUsing([
                            'rd_bridge_cd',
                            'rd_system_id',
                            'bridge_type_cd',
                            'bridge_name',
                            'chainage',
                            'bridge_lane',
                            'river_name',
                            'cd_bridge_length',
                            'construction_type_cd',
                            'year_of_construction',
                            'no_of_span',
                            'span_length',
                            'kerb_height',
                            'year_of_rehabilitation',
                            'no_of_piers',
                            'abutment_type_cd',
                            'super_structure_type_cd',
                            'handrail_type_cd',
                            'deck_type_cd',
                            'carriage_width',
                            'guard_stone',
                            'load_capacity',
                            'signs',
                            'lowest_water_level',
                            'highest_flood_level',
                            'rfl',
                            'source_depth',
                            'discharge',
                            'deck_level',
                            'footh_path',
                            'expansion_join_cd',
                            'bridge_condition',
                            'next_schedule_inspection_date',
                            'created_at_office_cd',
                            'bridge_number',
                            'bridge_location',
                            'date_of_last_inspection',
                            'kerb_width',
                            'minimum_water_level',
                            'bridge_remark',
                            'bridge_width',
                            'has_head_wall',
                            'has_wing_wall',
                            'has_retain_wall',
                            'has_abutment_wall',
                            'has_safety_apron',
                            'safety_apron_type',
                            'safety_apron_hand_rail_type',
                            'apron_width',
                            'sent_for_finalize',
                            'asset_plan_id',
                            'created_at',
                            'updated_at',
                            'updated_by',
                            'created_by',
                        ], function ($query) use ($assetCd, $assetPlanId, $currentTime, $userId) {
                            $query->from('public.asset_road_bridge_details')
                                ->where('rd_bridge_cd', $assetCd)
                                ->whereNotExists(function ($exists) use ($assetCd) {
                                    $exists->select(DB::raw(1))
                                        ->from('public.asset_road_bridge_details_draft')
                                        ->where('rd_bridge_cd', $assetCd);
                                })
                                ->select(
                                    'rd_bridge_cd',
                                    'rd_system_id',
                                    'bridge_type_cd',
                                    'bridge_name',
                                    'chainage',
                                    'bridge_lane',
                                    'river_name',
                                    'cd_bridge_length',
                                    'construction_type_cd',
                                    'year_of_construction',
                                    'no_of_span',
                                    'span_length',
                                    'kerb_height',
                                    'year_of_rehabilitation',
                                    'no_of_piers',
                                    'abutment_type_cd',
                                    'super_structure_type_cd',
                                    'handrail_type_cd',
                                    'deck_type_cd',
                                    'carriage_width',
                                    'guard_stone',
                                    'load_capacity',
                                    'signs',
                                    'lowest_water_level',
                                    'highest_flood_level',
                                    'rfl',
                                    'source_depth',
                                    'discharge',
                                    'deck_level',
                                    'footh_path',
                                    'expansion_join_cd',
                                    'bridge_condition',
                                    'next_schedule_inspection_date',
                                    'created_at_office_cd',
                                    'bridge_number',
                                    'bridge_location',
                                    'date_of_last_inspection',
                                    'kerb_width',
                                    'minimum_water_level',
                                    'bridge_remark',
                                    'bridge_width',
                                    'has_head_wall',
                                    'has_wing_wall',
                                    'has_retain_wall',
                                    'has_abutment_wall',
                                    'has_safety_apron',
                                    'safety_apron_type',
                                    'safety_apron_hand_rail_type',
                                    'apron_width',
                                    DB::raw("'N' as sent_for_finalize"),
                                    DB::raw("'" . $assetPlanId . "' as asset_plan_id"),
                                    'created_at',
                                    'updated_at',
                                    'updated_by',
                                    'created_by',
                                );
                        });

                        if ($copiedRows == 0 && DB::table('public.asset_road_bridge_details_draft')->where('rd_bridge_cd', $assetCd)->exists()) {
                            Log::warning('Duplicate road bridge details found in draft while redefining asset.', [
                                'asset_plan_id' => $assetPlanId,
                                'project_cd' => $projectCd,
                                'asset_cd' => $assetCd,
                                'parent_cd' => $parentCd,
                                'type_key' => $typeKey,
                            ]);
                        }

                        Log::info('Copied road bridge details to draft for redefine asset.', [
                            'asset_plan_id' => $assetPlanId,
                            'project_cd' => $projectCd,
                            'asset_cd' => $assetCd,
                            'parent_cd' => $parentCd,
                            'type_key' => $typeKey,
                            'copied_rows' => $copiedRows,
                        ]);


                        $roadDraftDetails = DB::table('public.asset_road_details_draft')
                            ->where('rd_system_id', $assetCd)
                            ->first();
                        $districts = AssetMasterLgdDistrict::all();
                        $roadTypes = AssetMasterRdType::all();
                        $roadOwners = AssetMasterRoadOwner::all();
                        $roadCategories = AssetMasterRoadCategory::all();
                        break;
                    case '12':
                        $copiedRows = DB::table('public.asset_protection_wall_details_draft')->insertUsing([
                            'protection_wall_cd',
                            'rd_system_id',
                            'chainage',
                            'wall_type_cd',
                            'structure_type_cd',
                            'bottom_width',
                            'top_width',
                            'length',
                            'height',
                            'created_at_office_cd',
                            'remarks',
                            'year_of_construction',
                            'year_of_renovation',
                            'lat',
                            'lon',
                            'asset_plan_id',
                            'sent_for_finalize',
                            'created_at',
                            'updated_at',
                            'updated_by',
                            'created_by'
                        ], function ($query) use ($assetCd, $assetPlanId, $currentTime, $userId) {
                            $query->from('public.asset_protection_wall_details')
                                ->where('protection_wall_cd', $assetCd)
                                ->whereNotExists(function ($exists) use ($assetCd) {
                                    $exists->select(DB::raw(1))
                                        ->from('public.asset_protection_wall_details_draft')
                                        ->where('protection_wall_cd', $assetCd);
                                })
                                ->select(
                                    'protection_wall_cd',
                                    'rd_system_id',
                                    'chainage',
                                    'wall_type_cd',
                                    'structure_type_cd',
                                    'bottom_width',
                                    'top_width',
                                    'length',
                                    'height',
                                    'created_at_office_cd',
                                    'remarks',
                                    'year_of_construction',
                                    'year_of_renovation',
                                    'lat',
                                    'lon',
                                    DB::raw("'" . $assetPlanId . "' as asset_plan_id"),
                                    DB::raw("'N' as sent_for_finalize"),
                                    'created_at',
                                    'updated_at',
                                    'updated_by',
                                    'created_by'
                                );
                        });

                        if ($copiedRows == 0 && DB::table('public.asset_protection_wall_details_draft')->where('protection_wall_cd', $assetCd)->exists()) {
                            Log::warning('Duplicate Protection Wall details found in draft while redefining asset.', [
                                'asset_plan_id' => $assetPlanId,
                                'project_cd' => $projectCd,
                                'asset_cd' => $assetCd,
                                'parent_cd' => $parentCd,
                                'type_key' => $typeKey,
                            ]);
                        }

                        Log::info('Copied Protection Wall details to draft for redefine asset.', [
                            'asset_plan_id' => $assetPlanId,
                            'project_cd' => $projectCd,
                            'asset_cd' => $assetCd,
                            'parent_cd' => $parentCd,
                            'type_key' => $typeKey,
                            'copied_rows' => $copiedRows,
                        ]);
                        break;
                }
            }
            DB::commit();


            $copyMessage = $copiedRows > 0
                ? 'Road details copied to draft successfully.'
                : 'Road details already exist in draft !!!You Can Still Edit the Draft Road Data.';

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Failed to copy road details to draft for redefine asset.', [
                'asset_plan_id' => $assetPlanId,
                'project_cd' => $projectCd,
                'asset_cd' => $assetCd,
                'parent_cd' => $parentCd,
                'type_key' => $typeKey,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $copyMessage = 'Unable to copy road details to draft. Please check logs.';
            return view('error');
        }

        if ($copiedRows > 0)
            $copyStatus = true;


        if ($dept_cd == 14) {
            session(['system_id' => $parentCd]);
            $roadDetails = AssetRoadDetail::find($parentCd);
            session(['road_name' => $roadDetails->rd_name]);
            session(['road_number' => $roadDetails->rd_number]);
            session(['road_length' => $roadDetails->road_length]);
            switch ($typeKey) {
                case '10':
                    return view('pms.redefine.assets.index', compact(
                        'assetPlanId',
                        'projectCd',
                        'assetCd',
                        'parentCd',
                        'typeKey',
                        'copyStatus',
                        'copyMessage',
                        'roadDraftDetails',
                        'roadTypes',
                        'roadOwners',
                        'roadCategories',
                        'districts'
                    ));

                case '0':
                    session(['culvert_id' => $assetCd]);
                    return redirect()->route('editCDWorks', [
                        'id' => $assetCd,   // this maps to {id} in route
                        'assetPlanId' => $assetPlanId,
                        'projectCd' => $projectCd,
                        'assetCd' => $assetCd,
                        'parentCd' => $parentCd,
                        'typeKey' => $typeKey,
                        'redefineAssetFromProject' => true
                    ]);
                case '1':
                    session(['bridgeId' => $assetCd]);
                    return redirect()->route('bridge.store', [
                        'assetPlanId' => $assetPlanId,
                        'projectCd' => $projectCd,
                        'assetCd' => $assetCd,
                        'parentCd' => $parentCd,
                        'typeKey' => $typeKey,
                        'redefineAssetFromProject' => true,
                    ]);
                case '12':
                    return redirect()->route('editProtectionWall', [
                        'id' => $assetCd,   // this maps to {id} in route
                        'assetPlanId' => $assetPlanId,
                        'projectCd' => $projectCd,
                        'assetCd' => $assetCd,
                        'parentCd' => $parentCd,
                        'typeKey' => $typeKey,
                        'redefineAssetFromProject' => true
                    ]);
            }
        }
        return view('error');
    }
}
