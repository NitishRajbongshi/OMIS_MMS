<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterDocumentCategory extends Model
{
    use HasFactory;
    protected $table = 'asset_master_document_category';
    protected $fillable = [
        'doc_catg_cd',
        'doc_catg_descr'
    ];
}
