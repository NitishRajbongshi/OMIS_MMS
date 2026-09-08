<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadCdworkHeadWallDetailsHist extends Model
{
    use HasFactory;
    protected $table = 'asset_road_cdwork_head_wall_details_hist';
    protected $fillable = [
        'head_wall_sr_no',
        'rd_cdwork_cd',
        'head_wall_type_cd',
        'head_wall_stream_type_cd',
        'head_wall_length',
        'top_width',
        'head_wall_heigth',
        'created_by',
        'hist_created_at',
        'hist_updated_at',
        'hist_remarks',
        'bottom_width'
    ];
}
