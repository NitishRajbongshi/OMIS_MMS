<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterToeWallType extends Model
{
    use HasFactory;

    protected $fillable = [
        'toe_wall_type_cd',
        'toe_wall_type_descr'
    ];
}
