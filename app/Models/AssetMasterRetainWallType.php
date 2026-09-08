<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterRetainWallType extends Model
{
    use HasFactory;

    protected $fillable = [
        'retain_type_cd',
        'reatain_wall_descr'
    ];
}
