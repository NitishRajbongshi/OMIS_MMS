<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterCircle extends Model
{
    use HasFactory;

    protected $fillable = [
        'circle_cd',
        'circle_name',
        'zone_cd',
        'district_cd',
        'state_cd',
        'dept_cd'
    ];
}
