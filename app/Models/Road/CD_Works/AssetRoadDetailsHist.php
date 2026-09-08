<?php

namespace App\Models\Road;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadDetailsHist extends Model
{
    use HasFactory;

    protected $table = 'asset_road_details_hist';
    protected $primaryKey = 'id';
}
