<?php

namespace App\Models\Mechanical\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterVehicleMaker extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_mechanicals';
    protected $fillable = [
        'maker_cd',
        'maker_name'
    ];
}
