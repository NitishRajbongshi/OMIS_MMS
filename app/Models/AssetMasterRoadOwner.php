<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterRoadOwner extends Model
{
    use HasFactory;
    protected $table = 'asset_master_road_owner';
    // protected $primaryKey = 'owner_cd';

    protected $fillable = [
        'owner_cd', 
        'owner_name'
    ];
}
