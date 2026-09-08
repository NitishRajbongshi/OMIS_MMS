<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterWingWallType extends Model
{
    use HasFactory;

    protected $fillable = [
        'wing_wall_type_cd',
        'wing_wall_type_descr'
    ];
}
