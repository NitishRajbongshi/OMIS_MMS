<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterSurfaceType extends Model
{
    use HasFactory;

    protected $table = 'asset_master_surface_type';
    // protected $primaryKey = 'surface_cd';

    protected $fillable = [
        'surface_cd',
        'surface_descr',
        'pavement_type_cd'
    ];
}
