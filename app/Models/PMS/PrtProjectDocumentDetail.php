<?php

namespace App\Models\PMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrtProjectDocumentDetail extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $table = 'projects.prt_project_document_details';
    protected $fillable = [
        'request_id',
        'project_cd',
        'file_path',
        'file_type',
        'doc_catg',
        'created_by',
        'updated_by',
    ];
}
