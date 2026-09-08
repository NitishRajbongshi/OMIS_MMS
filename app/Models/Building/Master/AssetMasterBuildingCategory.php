<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingCategory extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    protected $table = 'buildings.asset_master_building_category';
    protected $fillable = [
        'building_catg_cd',
        'building_catg_descr'
    ];
}
