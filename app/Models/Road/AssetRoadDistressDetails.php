<?php

namespace App\Models\Road;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadDistressDetails extends Model
{
    use HasFactory;
    protected $table = 'asset_road_distress_details';
    protected $primaryKey = 'rd_distress_cd';
}
