<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterVerificationStatus extends Model
{
    use HasFactory;
    protected $table = 'public.asset_master_verification_status';
    protected $fillable = [
        'status_cd',
        'status_descr'
    ];
}
