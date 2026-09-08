<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingCondition extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    // protected $primaryKey = 'condtion_cd';
    protected $fillable = [
        'condtion_cd',
        'condtion_descr'
    ];
}
