<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterRoadCategory extends Model
{
    use HasFactory;

    protected $table = 'asset_master_road_category';
    // protected $primaryKey = 'rd_catg_cd';
    protected $fillable = [
        'rd_catg_cd',
        'rd_catg_descr',
        'rd_catg_short_code'
    ];
}
