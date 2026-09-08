<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    // protected $primaryKey = 'building_type_cd';
    protected $fillable = [
        'building_type_cd',
        'building_type_descr',
        'building_class_cd'
    ];
}
