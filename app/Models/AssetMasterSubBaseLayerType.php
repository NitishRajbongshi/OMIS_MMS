<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterSubBaseLayerType extends Model
{
    use HasFactory;

    protected $primaryKey = 'sub_base_layer_type_cd';
    protected $fillable = [
        'sub_base_layer_type_cd',
        'sub_base_layer_type_descr'
    ];
}
