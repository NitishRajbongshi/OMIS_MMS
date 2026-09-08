<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingUnitType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    protected $fillable = [
        'unit_type_cd',
        'unit_type_name'
    ];
}
