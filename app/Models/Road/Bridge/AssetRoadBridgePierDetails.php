<?php

namespace App\Models\Road\Bridge;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadBridgePierDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'pier_sr_no',
        'rd_bridge_cd',
        'pier_type_cd',
        'foundation_type_cd',
        'pier_length',
        'pier_width',
        'pier_height',
        'created_by',
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
