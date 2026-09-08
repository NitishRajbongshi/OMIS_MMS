<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterRdType extends Model
{
    use HasFactory;

    protected $table = 'asset_master_rd_type';
    protected $primaryKey = 'rd_type_cd';

    protected $fillable = [
        'rd_type_cd', 
        'rd_type_descr', 
    ];
}
