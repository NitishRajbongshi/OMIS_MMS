<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterZone extends Model
{
    use HasFactory;
    protected $primaryKey = 'zone_cd';
    protected $fillable = [
        'zone_cd',
        'zone_name',
        'district_cd',
        'state_cd',
        'dept_cd'
    ];
}
