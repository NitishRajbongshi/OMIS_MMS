<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterFaceWallType extends Model
{
    use HasFactory;
    protected $fillable = [
        'face_wall_type_cd',
        'face_wall_type_descr'
    ];
}
