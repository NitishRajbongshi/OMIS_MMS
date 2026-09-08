<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblNotificationDtls extends Model
{
    use HasFactory;

    protected $table = 'tbl_notification_dtls';
    protected $primaryKey = 'notice_cd';

    protected $fillable = [
        'notice_cd',
        'notice_title',
        'notice_desc',
        'dept_cd',
        'date_expiry',
        'is_expired',
        'created_by'
    ];
}
