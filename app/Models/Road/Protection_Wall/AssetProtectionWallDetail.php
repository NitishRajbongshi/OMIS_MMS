<?php

namespace App\Models\Road\Protection_Wall;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetProtectionWallDetail extends Model
{
    use HasFactory;
    protected $table = 'public.asset_protection_wall_details';
    protected $primaryKey = 'protection_wall_cd';
    protected $fillable = [
        'protection_wall_cd',
        'rd_system_id',
        'chainage',
        'wall_type_cd',
        'structure_type_cd',
        'bottom_width',
        'top_width',
        'length',
        'height',
        'updated_by',
        'created_by',
        'created_at_office_cd',
        'remarks',
        'year_of_construction',
        'year_of_renovation',
        'approved_by',
        'approved_at',
        // Saiful # 21-04-2026 # Start
        'asset_plan_id'
        // Saiful # 21-04-2026 # End
    ];
}
