<?php

namespace App\Models\PMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrtProjectImageDetail extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $table = 'projects.prt_project_images_details';
    protected $fillable = [
        'request_id',
        'project_cd',
        'image_path',
        'file_type',
        'created_by',
        'updated_by',
    ];
}
