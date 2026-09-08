<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetUserQualificationsDtl extends Model
{
    use HasFactory;
    // protected $table = '';
    protected $primaryKey = 'academicqualid';
    protected $fillable = [
        'academicqualid',
        'qualificationid',
        'user_id',
        'ispublished',
        'created_by',
        'updated_by',
        'details'
    ];
}
