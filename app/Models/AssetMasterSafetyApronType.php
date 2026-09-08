<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterSafetyApronType extends Model
{
    use HasFactory;
    protected $table = 'asset_master_safety_apron_types';
    protected $primaryKey = 'apron_type_cd';
    protected $fillable = [
        'apron_type_cd',
        'apron_type_descr'
    ];
}
