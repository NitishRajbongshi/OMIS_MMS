<?php

namespace App\Models\Mechanical;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterVehicleCondition extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_mechanicals';
    // protected $primaryKey = 'condition_cd';
    protected $fillable = [
        'condition_cd',
        'condition_descr',
    ];
}
