<?php

namespace App\Models\PMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrtContractorDocumentDetails extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $table = 'projects.prt_contractor_document_details';
    protected $fillable = [
        'regn_no',
        'file_path',
        'file_type',
        'doc_catg',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
