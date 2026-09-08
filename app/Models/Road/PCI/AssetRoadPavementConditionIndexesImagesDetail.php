<?php

namespace App\Models\Road\PCI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadPavementConditionIndexesImagesDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'pci_section_cd',
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
