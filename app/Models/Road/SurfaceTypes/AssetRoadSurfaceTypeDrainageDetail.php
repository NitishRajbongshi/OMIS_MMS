<?php

namespace App\Models\Road\SurfaceTypes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadSurfaceTypeDrainageDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'rd_system_id',
        'rd_surface_cd',
        'start_chainage',
        'end_chainage',
        'drainage_length',
        'type_of_line_drainage',
        'created_at_office_cd',
        'created_by',
        'updated_by',
        'drainage_side'
    ];
}
