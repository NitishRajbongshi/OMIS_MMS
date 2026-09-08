<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingColumnType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    protected $fillable = [
        'column_type_cd',
        'column_type_descr'
    ];
}
