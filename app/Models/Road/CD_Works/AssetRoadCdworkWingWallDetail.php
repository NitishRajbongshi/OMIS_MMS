<?php

namespace App\Models\Road\CD_Works;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadCdworkWingWallDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'wing_wall_sr_no',
        'rd_cdwork_cd',
        'top_width',
        'bottom_width',
        'height1',
        'height2',
        'created_by',
        'wing_wall_type_cd',
        'thickness',
        'slope',
        'transitions',
        'angle',
        'radius',
        'length'
    ];
}