<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBlock extends Model
{
    use HasFactory;
    protected $table = 'public.asset_master_block';
    // protected $primaryKey = 'bridge_type_cd';

    protected $fillable = [
        'block_cd', 
        'block_name',
        'district_cd',
        'state_cd',
    ];
}
