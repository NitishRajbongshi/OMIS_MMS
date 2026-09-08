<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterDrainageType extends Model
{
    use HasFactory;

    protected $fillable = [
        'drainage_cd',
        'drainage_descr'
    ];
}
