<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingFoundationType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    // protected $primaryKey = 'foundation_cd';
    protected $fillable = [
        'foundation_cd',
        'foundation_descr'
    ];
}
