<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingSecurityFenchingType extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_buildings';
    // protected $primaryKey = 'boundary_cd';
    protected $fillable = [
        'fenching_type_cd',
        'fenching_type_descr'
    ];
}
