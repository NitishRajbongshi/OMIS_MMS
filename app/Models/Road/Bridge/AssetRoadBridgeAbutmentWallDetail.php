<?php

namespace App\Models\Road\Bridge;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadBridgeAbutmentWallDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'abutment_wall_sr_no',
        'rd_bridge_cd',
        'abutment_wall_type_cd',
        'abutment_wall_length',
        'abutment_wall_width',
        'abutment_wall_heigth',
        'created_by',
        'hist_remarks',
        'foundation_type_cd',
        'bearing_type_cd',
        'pile_diameter',
        'pile_length',
        'pile_type_cd',
        'well_type_cd',
        'well_size',
        'open_foundation_size',
        'open_foundation_depth'
    ];
}
