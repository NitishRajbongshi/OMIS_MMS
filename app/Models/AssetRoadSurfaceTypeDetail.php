<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadSurfaceTypeDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'rd_system_id',
        'rd_surface_cd',
        'surface_condition_cd',
        'start_chainage',
        'end_chainage',
        'created_at_office_cd',
        'base_layer_type',
        'base_layer_thickness',
        'sub_base_layer_type',
        'sub_base_layer_thickness',
        'pavement_type',
        'shoulder_type',
        'land_slide',
        'last_maintenance_date',
        'created_by',
        'updated_by',
        'construction_year',
        'surface_type_cd',
        'surface_width',
        'shoulder_width',
        'base_cbr',
        'base_pi',
        'sub_base_cbr',
        'sub_base_pi',
        'maintenance_type',
        'drainage',
        'approved_by',
        'approved_at'
    ];
}
