<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterRoadCondition extends Model
{
    use HasFactory;

    protected $table = 'asset_master_road_condition';
    protected $primaryKey = 'rd_condition_cd';

    protected $fillable = [
        'rd_condition_cd',
        'rd_condition_descr'
    ];
}
