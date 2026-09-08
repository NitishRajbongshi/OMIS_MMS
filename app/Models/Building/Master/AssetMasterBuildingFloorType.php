<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingFloorType extends Model
{
    use HasFactory;
    protected $table = 'buildings.asset_master_building_floor_types';
    protected $fillable = [
        'floor_type_cd',
        'floor_type_descr'
    ];
}
