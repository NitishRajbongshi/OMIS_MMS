<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterMpConst extends Model
{
    use HasFactory;
    protected $table = 'asset_master_mp_const';
    protected $fillable = [
        'const_cd',
        'const_desc'
    ];
}
