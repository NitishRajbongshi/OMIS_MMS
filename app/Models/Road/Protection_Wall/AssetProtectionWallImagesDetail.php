<?php

namespace App\Models\Road\Protection_wall;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetProtectionWallImagesDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'protection_wall_cd',
        'rd_system_id',
        'image_path',
        'file_type',
        'created_by',
        'updated_by',
        'created_at_office_cd',
        'lat',
        'lon'
    ];
}
