<?php

namespace App\Models\Road\Bridge;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadBridgeHeadWallDetail extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'head_wall_sr_no',
        'rd_bridge_cd',
        'head_wall_type_cd',
        'head_wall_length',
        'head_wall_width',
        'head_wall_heigth',
        'created_by',
        'thickness',
        'head_wall_stream_type_cd',
        'thickness'
    ];
}
