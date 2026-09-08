<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterCatchPitType extends Model
{
    use HasFactory;

    protected $fillable = [
        'catch_pit_type_cd',
        'catch_pit_type_descr'
    ];
}
