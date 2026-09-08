<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterDesignationOfficeTypeMappings extends Model
{
    use HasFactory;

    protected $fillble = [
        'desg_cd',
        'office_type_cd'
    ];
}
