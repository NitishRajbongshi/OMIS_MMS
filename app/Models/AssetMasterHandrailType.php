<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterHandrailType extends Model
{
    use HasFactory;
    protected $table = 'asset_master_handrail_types';
    protected $primaryKey = 'hand_rail_type_cd';

    protected $fillable = [
        'hand_rail_type_cd',
        'hand_rail_type_descr',
    ];
}
