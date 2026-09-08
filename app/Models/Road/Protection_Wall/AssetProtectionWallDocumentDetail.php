<?php

namespace App\Models\Road\Protection_wall;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetProtectionWallDocumentDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'protection_wall_cd',
        'rd_system_id',
        'file_path',
        'file_type',
        'doc_catg',
        'created_by',
        'updated_by',
        'created_at_office_cd'
    ];
}
