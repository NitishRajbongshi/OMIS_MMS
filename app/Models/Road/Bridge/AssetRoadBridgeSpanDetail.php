<?php

namespace App\Models\Road\Bridge;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadBridgeSpanDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'span_sr_no',
        'rd_bridge_cd',
        'span_length',
        'created_by',
    ];
}
