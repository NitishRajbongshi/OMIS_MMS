<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterHeadWall extends Model
{
    use HasFactory;

    protected $fillable = [
        'head_wall_cd',
        'head_wall_descr'
    ];
}
