<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterSuperStructureType extends Model
{
    use HasFactory;

    protected $table = 'asset_master_super_structure_types';
    protected $primaryKey = 'st_type_cd';

    protected $fillable = [
        'st_type_cd',
        'st_type_descr',
    ];
}
