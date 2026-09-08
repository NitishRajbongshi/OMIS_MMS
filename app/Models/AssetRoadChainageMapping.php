<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadChainageMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'rd_system_id',
        'chainage_from',
        'chainage_to',
        'zone_cd',
        'circle_cd',
        'division_cd',
        'sub_division_cd',
        'remaining_chainage_length',
        'chainage_step_id',
        'chainage_created_at_office_cd',
        'chainage_created_by',
        'chainage_updated_by',
        'calculated_length',
        'chainage_created_at',
        'is_chainage_completed_for_down_level',
        'parent_pk_id'
    ];
}
