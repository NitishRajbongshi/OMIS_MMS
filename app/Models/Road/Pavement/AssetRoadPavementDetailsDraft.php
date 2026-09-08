<?php

namespace App\Models\Road\Pavement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadPavementDetailsDraft extends Model
{
    use HasFactory;
    protected $table = 'asset_road_pavement_details_draft';
    protected $primaryKey = 'rd_pavement_cd';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'rd_pavement_cd',
        'rd_system_id',
        'start_chainage',
        'end_chainage',
        'pavement_type_cd',
        'formation_width',
        'carriage_width',
        'sub_base_layer_type_cd',
        'base_layer_type_cd',
        'surface_type_cd',
        'sub_base_lyr_thickness',
        'base_lyr_thickness',
        'surface_lyr_thickness',
        'surface_condition',
        'has_shoulder',
        'has_drainage',
        'is_land_slide_prone',
        'year_of_construction',
        'construction_cost',
        'lat',
        'lng',
        'remarks',
        'created_by',
        'updated_by',
        'created_at_office_cd',
        'sent_for_finalize',
        'sent_for_finalize_on',
        'sent_for_finalize_by',
        'is_rejected',
        'reason_of_rejection',
        'date_of_rejection',
        'rejected_by',
        // Saiful # 21-04-2026 # Start
        'asset_plan_id'
        // Saiful # 21-04-2026 # End
    ];
}
