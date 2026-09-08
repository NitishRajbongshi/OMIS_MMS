<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVehicleMappingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'regn_no',
        'chassis_no',
        'engine_no',
        'vehicle_type',
        'model',
        'maker',
        'fuel_type'
    ];
}
