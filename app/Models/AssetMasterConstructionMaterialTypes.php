<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterConstructionMaterialTypes extends Model
{
    use HasFactory;

    protected $fillable = [
        'const_material_type_cd',
        'const_material_type_descr'
    ];
}
