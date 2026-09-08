<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterProtectionWallType extends Model
{
    use HasFactory;
    protected $table = 'asset_master_protection_wall_type';
    protected $fillable = [
        'wall_type_cd',
        'wall_type_descr'
    ];
}
