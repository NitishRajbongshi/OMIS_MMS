<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterSubDivision extends Model
{
    use HasFactory;
	protected $table = 'public.asset_master_sub_divisions';
    protected $fillable = [
        'sub_div_cd',
        'sub_div_name',
        'div_cd',
        'circle_cd',
        'zone_cd',
        'district_cd',
        'state_cd',
        'dept_cd'
    ];
}
