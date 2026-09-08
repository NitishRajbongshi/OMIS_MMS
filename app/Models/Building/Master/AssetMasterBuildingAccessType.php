<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingAccessType extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_buildings';
    // protected $primaryKey = 'boundary_cd';
    protected $fillable = [
        'access_type_cd',
        'access_type_descr'
    ];
}
