<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingWallType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    protected $primaryKey = 'wall_type_cd';
    protected $fillable = [
        'wall_type_cd',
        'wall_type_descr'
    ];
}
