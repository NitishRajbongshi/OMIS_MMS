<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterWellType extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'well_type_cd',
        'well_type_descr'
    ];
}
