<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterRdCdWorksType extends Model
{
    use HasFactory;

    protected $table = 'asset_master_rd_cdworks_type';
    protected $primaryKey = 'rd_type_cd';

    protected $fillable = [
        'cdwork_cd', 
        'cdwoerk_descr'
    ];
}
