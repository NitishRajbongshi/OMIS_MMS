<?php

namespace App\Models\Road\CD_Works;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadCdworkHeadWallDraftDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'head_wall_sr_no',
        'rd_cdwork_cd',
        'head_wall_type_cd',
        'head_wall_stream_type_cd',
        'head_wall_length',
        'top_width',
        'head_wall_heigth',
        'created_by',
        'bottom_width'
    ];
}
