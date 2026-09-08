<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingSlabType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    // protected $primaryKey = 'purpose_cd';
    protected $fillable = [
        'slab_type_cd',
        'slab_type_descr'
    ];
}
