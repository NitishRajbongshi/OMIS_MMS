<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoleDetailHist extends Model
{
    use HasFactory;
    protected $table = 'user_role_details_hist';
    protected $fillable = [
        'user_id',
        'role_id',
        'inserted_by',
        'hist_created_at',
        'hist_created_by'
    ];
}
