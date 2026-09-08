<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterDrainageLineDrainageType extends Model
{
    use HasFactory;
    protected $fillable = [
        'line_drainage_type_cd',
        'line_drainage_type_descr'
    ];
}
