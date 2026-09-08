<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingStaircaseType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    protected $fillable = [
        'staircase_type',
        'staircase_descr'
    ];
}
