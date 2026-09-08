<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterQualification extends Model
{
    use HasFactory;
    protected $fillable = [
        'qualificationid',
        'code',
        'details',
        'ispublished',
        'created_by'
    ];
}
