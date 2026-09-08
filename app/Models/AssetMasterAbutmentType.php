<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterAbutmentType extends Model
{
    use HasFactory;

    protected $table = 'asset_master_abutment_types';
    protected $primaryKey = 'abutment_type_cd';

    protected $fillable = [
        'abutment_type_cd',
        'abutment_type_descr',
    ];
}
