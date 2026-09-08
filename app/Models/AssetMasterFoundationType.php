<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterFoundationType extends Model
{
    use HasFactory;
    protected $table = 'asset_master_foundation_types';
    protected $primaryKey = 'foundation_cd';

    protected $fillable = [
        'foundation_cd',
        'foundation_descr',
    ];
}
