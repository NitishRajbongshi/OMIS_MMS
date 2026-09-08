<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterPavementType extends Model
{
    use HasFactory;

    protected $primaryKey = 'pavement_type_cd';
    protected $fillable = [
        'pavement_type_cd',
        'pavement_type_descr'
    ];
}
