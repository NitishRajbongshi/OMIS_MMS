<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetUserMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_email',
        'office_type_cd',
        'zone_cd',
        'circle_cd',
        'division_cd',
        'sub_division_cd',
        'office_cd'
    ];
}
