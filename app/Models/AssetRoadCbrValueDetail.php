<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadCbrValueDetail extends Model
{
    use HasFactory;

    // protected $table = 'asset_road_cbr_value_details';

    protected $fillable = [
        'rd_system_id', 
        'cbr_start_chainage', 
        'cbr_end_chainage', 
        'cbr_segment_length', 
        'cbr_value',
        'tot_road_length', 
        'tot_segment_length', 
        'remaining_length', 
        'created_by',
        'updated_by'
    ];
}
