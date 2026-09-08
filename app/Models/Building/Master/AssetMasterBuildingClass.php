<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingClass extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    protected $table = 'buildings.asset_master_building_class';
    protected $fillable = [
        'building_class_cd',
        'building_class_descr'
    ];
}
