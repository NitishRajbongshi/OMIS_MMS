<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetUnlockSupportiveDocuments extends Model
{
    use HasFactory;
    protected $table = 'asset_unlock_supportive_documents';
    protected $primaryKey = 'unlock_supportive_cd';
    protected $fillable = [
        'unlock_supportive_cd',
        'unlock_cd',
        'file_path',
        'file_type',
        'created_at',
        'updated_at',
        'created_by'
    ];
}
