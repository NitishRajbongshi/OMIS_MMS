<?php

namespace App\Models\Road\PCI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadPavementConditionIndexesDocumentDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'pci_section_cd',
        'rd_system_id',
        'file_path',
        'file_type',
        'doc_catg',
        'created_by',
        'updated_by',
        'created_at_office_cd'
    ];
}
