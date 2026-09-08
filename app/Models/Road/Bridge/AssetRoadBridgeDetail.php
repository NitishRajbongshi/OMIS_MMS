<?php

namespace App\Models\Road\Bridge;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadBridgeDetail extends Model
{
    use HasFactory;

    protected $table = 'asset_road_bridge_details';
    protected $primaryKey = 'rd_bridge_cd';

    protected $fillable = [
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
        'kerb_distance', // new code start by Pulak
        'foundation_type_cd',
        'year_of_rehabilitation',
        'no_of_piers',
        'pier_size',
        'abutment_type_cd',
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
        'super_structure_type_cd',
        'footh_path',
        'bearings',
        'expansion_join_cd',
        'bridge_condition',
        'next_schedule_inspection_date',
        'updated_by',
        'created_by',
        'created_at_office_cd',
        'bridge_number',
        'bridge_location',
        'date_of_last_inspection',
        'kerb_width',
        'minimum_water_level',
        'bridge_remark',
        'pile_diameter',
        'pile_length',
        'pile_type',
        'well_type',
        'well_size',
        'open_foundation_size',
        'depth_open_foundation_size',
        'bridge_width',
        'has_abutment_wall',
        'has_wing_wall',
        'has_head_wall',
        'has_retain_wall',
        'has_safety_apron',
        'safety_apron_type',
        'safety_apron_hand_rail_type',
        'apron_width',
        'approved_by',
        'approved_at',
        //Saiful # 29-04-2026 # Start
        'asset_plan_id',
        'updated_at',
        //Saiful # 29-04-2026 # End
    ];
}
