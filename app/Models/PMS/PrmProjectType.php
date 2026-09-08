<?php

namespace App\Models\PMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrmProjectType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $fillable = [
        'proj_type_cd',
        'proj_type_descr',
        'is_published'
    ];
}
