<?php

namespace App\Models\Road\Pavement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadPavementLandSlideDetail extends Model
{
    use HasFactory;
    protected $primaryKey = 'pv_land_slide_cd';

    protected $fillable = [
        'rd_pavement_cd',
        'land_slide_start_chainage',
        'land_slide_end_chainage',
        'severity_cd',
        'created_by',
        'updated_by',
        'created_at_office_cd'
    ];
}
