<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBridgeType extends Model
{
    use HasFactory;

    protected $table = 'asset_master_bridge_type';
    // protected $primaryKey = 'bridge_type_cd';

    protected $fillable = [
        'bridge_type_cd', 
        'bridge_type_descr'
    ];
}
