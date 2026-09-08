<?php

namespace App\Models\Road\Bridge;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadBridgeRetainWallDraftDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'retain_wall_sr_no',
        'rd_bridge_cd',
        'retain_wall_type_cd',
        'retain_wall_length',
        'retain_wall_width',
        'retain_wall_heigth',
        'created_by'
    ];
}
