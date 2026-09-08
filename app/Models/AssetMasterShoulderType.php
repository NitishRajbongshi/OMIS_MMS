<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterShoulderType extends Model
{
    use HasFactory;

    protected $primaryKey = 'shoulder_type_cd';
    protected $fillable = [
        'shoulder_type_cd',
        'shoulder_type_descr'
    ];
}
