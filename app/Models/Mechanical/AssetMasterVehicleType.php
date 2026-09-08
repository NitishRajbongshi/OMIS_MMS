<?php

namespace App\Models\Mechanical;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterVehicleType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_mechanicals';
    // protected $primaryKey = 'veh_type_cd';
    protected $fillable = [
        'veh_type_cd',
        'veh_type_descr',
    ];
}
