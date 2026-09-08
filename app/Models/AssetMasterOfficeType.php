<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterOfficeType extends Model
{
    use HasFactory;

    protected $table = 'asset_master_office_types';
    // protected $primaryKey = 'office_type_cd';

    protected $fillable = [
        'office_type_cd', 
        'office_type_desc'
    ];
}
