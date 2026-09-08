<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingBeamType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    protected $fillable = [
        'beam_type_cd',
        'beam_type_descr'
    ];
}
