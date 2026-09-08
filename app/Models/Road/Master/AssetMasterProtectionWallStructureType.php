<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterProtectionWallStructureType extends Model
{
    use HasFactory;
    protected $table = 'asset_master_protection_wall_structure_type';
    protected $primaryKey = 'structure_type_cd';
    protected $fillable = [
        'structure_type_cd',
        'structure_type_descr'
    ];
}
