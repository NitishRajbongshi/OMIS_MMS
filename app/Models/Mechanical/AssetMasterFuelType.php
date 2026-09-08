<?php

namespace App\Models\Mechanical;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterFuelType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_mechanicals';
    // protected $primaryKey = 'fuel_type_cd';
    protected $fillable = [
        'fuel_type_cd',
        'fuel_type_descr',
    ];
}
