<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterLgdState extends Model
{
    use HasFactory;
    protected $table = 'asset_master_lgd_state';
    protected $fillable = [
        'state_code',
        'state_name'
    ];
}
