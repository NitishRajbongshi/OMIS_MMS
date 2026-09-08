<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterVillage extends Model
{
    use HasFactory;
    protected $table = 'asset_master_village';
    protected $primaryKey = 'id';
    protected $fillable = [
        'state_code',
        'state_name',
        'district_code',
        'district_name',
        'block_code',
        'block_name',
        'village_code',
        'village_name',
        'census2011_village_code'
    ];
}
