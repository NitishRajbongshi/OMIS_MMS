<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterLgdDistrict extends Model
{
    use HasFactory;
    protected $table = 'asset_master_lgd_district';
    protected $fillable = [
        'dist_code',
        'dist_name',
        'state_code'
    ];
}
