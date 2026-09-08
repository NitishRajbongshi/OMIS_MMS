<?php

namespace App\Models\Building;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetBuildingImagesDetail extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_buildings';
    protected $fillable = [
        'building_system_cd',
        'image_path',
        'file_type',
        'created_by',
        'updated_by',
        'created_at_office_cd',
        'lat',
        'lon'
    ];
}
