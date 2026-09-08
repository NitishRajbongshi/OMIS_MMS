<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterDivision extends Model
{
    use HasFactory;
	protected $table = 'public.asset_master_divisions';
    protected $fillable = [
        'division_cd',
        'division_name',
        'circle_cd',
        'zone_cd',
        'district_cd',
        'state_cd',
        'dept_cd'
    ];
}
