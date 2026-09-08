<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterHeadWallsStreamType extends Model
{
    use HasFactory;

    protected $fillable = [
        'stream_type_cd',
        'stream_descr'
    ];
}
