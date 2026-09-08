<?php

namespace App\Models\Mechanical;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetEquipmentDocumentDetail extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_mechanicals';
    protected $fillable = [
        'euipment_cd',
        'file_path',
        'file_type',
        'doc_catg',
        'created_by',
        'updated_by',
        'created_at_office_cd',
    ];
}
