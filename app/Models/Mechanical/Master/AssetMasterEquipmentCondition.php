<?php

namespace App\Models\Mechanical\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterEquipmentCondition extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_mechanicals';
    protected $fillable = [
        'condition_cd',
        'condition_descr'
    ];
}
