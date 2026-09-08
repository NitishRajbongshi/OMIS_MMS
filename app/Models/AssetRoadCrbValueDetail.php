<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadCrbValueDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'rd_system_id', 
        'crb_start_chainage', 
        'crb_end_chainage', 
        'crb_segment_length', 
        'crb_value',
        'tot_road_length', 
        'tot_segment_length', 
        'remaining_length', 
        'created_by',
        'updated_by'
    ];
}
