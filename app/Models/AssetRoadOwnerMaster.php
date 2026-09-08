<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadOwnerMaster extends Model
{
    use HasFactory;

    protected $table = 'asset_road_owner_master';
    // protected $primaryKey = 'owner_cd';

    protected $fillable = [
        'owner_cd', 
        'owner_name', 
    ];
}
