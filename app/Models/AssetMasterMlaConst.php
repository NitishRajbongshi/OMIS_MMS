<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterMlaConst extends Model
{
    use HasFactory;
    protected $table = 'asset_master_mla_const';
    protected $fillable = [
        'const_cd',
        'const_descr'
    ];
}
