<?php

namespace App\Models\Road\CD_Works;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadCdworkDocumentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'rd_cdwork_cd',
        'rd_system_id',
        'file_path',
        'file_type',
        'doc_catg',
        'created_by',
        'updated_by',
        'created_at_office_cd'
    ];
}
