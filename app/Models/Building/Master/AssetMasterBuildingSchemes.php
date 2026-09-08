<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingSchemes extends Model
{
    use HasFactory;
    protected $table = 'buildings.asset_master_building_schemes';
    protected $fillable = [
        'scheme_cd',
        'scheme_descr'
    ];
}
