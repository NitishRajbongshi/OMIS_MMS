<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetUnlockDetails extends Model
{
    use HasFactory;
    protected $table = 'asset_unlock_details';
    protected $primaryKey = 'unlock_cd';

    protected $fillable = [
        'unlock_cd',
        'asset_name',
        'asset_cd',
        'asset_type_cd',
        'unlock_details',
        'unlock_valid_for',
        'unlock_valid_upto',
        'unlock_assigned_office_cd',
        'unlock_assigned_user_id',
        'unlock_created_by',
        'unlock_status',
        'created_at',
        'updated_at'
    ];
}
