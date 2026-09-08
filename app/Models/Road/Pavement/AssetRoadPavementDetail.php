<?php

namespace App\Models\Road\Pavement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadPavementDetail extends Model
{
    use HasFactory;
    protected $primaryKey = 'rd_pavement_cd';
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
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
        'created_at_office_cd',
        // Saiful # 21-04-2026 # Start
        'asset_plan_id'
        // Saiful # 21-04-2026 # End
    ];
}
