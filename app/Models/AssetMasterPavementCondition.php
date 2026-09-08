<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterPavementCondition extends Model
{
    use HasFactory;

    // protected $table = 'asset_master_office_types';
    // protected $primaryKey = 'pv_condition_cd';
    protected $fillable = [
        'pv_condition_cd', 
        'pv_condition_descr'
    ];
}
