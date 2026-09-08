<?php

namespace App\Models\Road\Pavement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadPavementDocumentDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'rd_pavement_cd',
        'rd_system_id',
        'file_path',
        'file_type',
        'doc_catg',
        'created_by',
        'updated_by',
        'created_at_office_cd'
    ];
}
