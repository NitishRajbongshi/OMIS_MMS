<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesgDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'desg_name',
        'dept_cd'
    ];
}
