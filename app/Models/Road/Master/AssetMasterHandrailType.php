<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterHandrailType extends Model
{
    use HasFactory;

    protected $fillable = [
        'hand_rail_type_cd',
        'hand_rail_type_descr'
    ];
}
