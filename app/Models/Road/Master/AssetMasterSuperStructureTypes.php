<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterSuperStructureTypes extends Model
{
    use HasFactory;
    protected $fillable = [
        'st_type_cd',
        'st_type_descr'
    ];
}
