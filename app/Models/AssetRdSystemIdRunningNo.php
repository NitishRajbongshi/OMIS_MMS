<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRdSystemIdRunningNo extends Model
{
    use HasFactory;

    protected $table = 'asset_rd_system_id_running_no';
    protected $primaryKey = 'user_type';
    
    protected $fillable = [
        'user_type',
        'start_no',
        'end_no',
        'current_running_no',
        'expired',
        'rd_catg_short_code'
    ];
}
