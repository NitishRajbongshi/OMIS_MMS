<?php

namespace App\Models\Road\CD_Works;

use App\Models\Road\AssetRoadDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetRoadCdworkDetail extends Model
{
    use HasFactory;

    protected $table = 'asset_road_cdwork_details';
    protected $primaryKey = 'rd_cdwork_cd';

    protected $fillable = [
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
        'created_by',
        'updated_by',
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
        'culvert_location', // formate: lat, lon
        'outlet_type_cd',
        'catch_pit_availability',
        'catch_pit_type_cd',
        'catch_pit_width',
        'catch_pit_condition',
        'cdwork_remark',
        'cdwork_has_head_wall',
        'sent_for_finalize',
        'sent_for_finalize_on',
        'sent_for_finalize_by',
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
        'const_material_type_cd',
        'abutment_type_cd',
        'abutment_height',
        'bearing_type_cd',
        'approved_by',
        'approved_at',
        'asset_plan_id'
    ];

    public function road()
    {
        $this->belongsTo(AssetRoadDetail::class);
    }
}
