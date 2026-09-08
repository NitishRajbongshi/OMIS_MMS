<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingUsePurpose extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    // protected $primaryKey = 'purpose_cd';
    protected $fillable = [
        'purpose_cd',
        'purpose_descr'
    ];
}
