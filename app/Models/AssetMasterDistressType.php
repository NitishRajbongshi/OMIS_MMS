<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterDistressType extends Model
{
    use HasFactory;

    protected $table = 'asset_master_distress_type';
    protected $primaryKey = 'distress_type_cd';
}
