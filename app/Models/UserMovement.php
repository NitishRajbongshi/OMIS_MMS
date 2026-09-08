<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_dept',
        'user_desg',
        'user_office_type_cd',
        'user_office',
        'assign_from',
        'assign_to',
        'reason'
    ];
}
