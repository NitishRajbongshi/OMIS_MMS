<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBearingType extends Model
{
    use HasFactory;

    protected $primaryKey = 'bearing_type_cd';

    protected $fillable = [
        'bearing_type_cd', 
        'bearing_type_descr'
    ];
}
