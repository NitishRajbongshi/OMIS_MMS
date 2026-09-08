<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterDrainageSide extends Model
{
    use HasFactory;
    protected $fillable = [
        'drainage_side_cd',
        'drainage_side_descr'
    ];
}
