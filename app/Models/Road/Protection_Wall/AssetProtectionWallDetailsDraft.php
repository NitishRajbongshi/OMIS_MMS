<?php

namespace App\Models\Road\Protection_Wall;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetProtectionWallDetailsDraft extends Model
{
    use HasFactory;
    protected $table = 'public.asset_protection_wall_details_draft';
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
        'sent_for_finalize',
        'sent_for_finalize_on',
        'sent_for_finalize_by',
        'updated_by',
        'created_by',
        'created_at_office_cd',
        'remarks',
        'year_of_construction',
        'year_of_renovation',
        'is_rejected',
        'reason_of_rejection',
        'date_of_rejection',
        'rejected_by',
        // Saiful # 21-04-2026 # Start
        'asset_plan_id'
        // Saiful # 21-04-2026 # End
    ];
}
