<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterVillagePopulationByCensus extends Model
{
    use HasFactory;

    protected $fillable = [
        'village_code',
        'village_name',
        'st_population',
        'sc_population',
        'total_population',
        'lgd_dist_code',
        'census_dist_code',
        'block_code',
        'state_code',
        'census_year',
        'urban_or_village'
    ];
}
