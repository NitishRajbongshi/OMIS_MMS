<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterCulvertOutletType extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_type_cd',
        'outlet_type_descr'
    ];
}
