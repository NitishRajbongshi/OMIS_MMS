<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterConstructionType extends Model
{
    use HasFactory;

    protected $table = 'asset_master_construction_types';
    protected $primaryKey = 'construction_type_cd';

    protected $fillable = [
        'construction_type_cd',
        'construction_type_descr',
    ];
}
