<?php

namespace App\Models\Road;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetModificationRequestRoadAssets extends Model
{
    use HasFactory;
    protected $table = 'asset_modification_request_road_assets';
    protected $primaryKey = 'request_id';
    // protected $fillable = [
    //     'request_id',
    //     'asset_name',
    //     'rd_system_id',
    //     'is_sub_asset'

    // ];
}
