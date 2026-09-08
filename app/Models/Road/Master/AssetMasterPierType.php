<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterPierType extends Model
{
    use HasFactory;
    protected $fillable = [
        'pier_type_descr',
    ];
}
