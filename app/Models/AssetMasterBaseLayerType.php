<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBaseLayerType extends Model
{
    use HasFactory;
    protected $primaryKey = 'base_layer_type_cd';
    protected $fillable = [
        'base_layer_type_cd',
        'base_layer_type_descr',
        'pavement_type_cd'
    ];
}
