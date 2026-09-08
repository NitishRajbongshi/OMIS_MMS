<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingLocation extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
	protected $table = 'buildings.asset_master_building_locations';
    protected $primaryKey = 'location_cd';
    protected $fillable = [
        'location_cd',
        'location_name',
        'division_cd',
        'sub_division_cd',
        'building_class_cd'
    ];
}
