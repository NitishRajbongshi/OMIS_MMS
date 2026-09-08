<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterPileType extends Model
{
    use HasFactory;

    protected $fillable = [
        'pile_type_cd',
        'pile_type_descr'
    ];
}
