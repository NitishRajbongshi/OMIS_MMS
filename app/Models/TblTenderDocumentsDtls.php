<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblTenderDocumentsDtls extends Model
{
    use HasFactory;
    protected $table = 'tbl_tender_documents_dtls';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tender_cd',
        'file_path',
        'file_name',
        'file_type',
        'created_at',
        'updated_at',
        'created_by'
    ];
}
