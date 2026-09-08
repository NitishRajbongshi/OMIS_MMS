<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterMaintenanceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance_type_cd',
        'maintenance_type_descr'
    ];
}
