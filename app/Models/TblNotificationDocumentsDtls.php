<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblNotificationDocumentsDtls extends Model
{
    use HasFactory;

    protected $table = 'tbl_notification_documents_dtls';
    protected $primaryKey = 'id';

    protected $fillable = [
        'notice_cd',
        'file_path',
        'file_name',
        'file_type',
        'created_at',
        'updated_at',
        'created_by'
    ];
}
