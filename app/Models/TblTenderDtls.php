<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblTenderDtls extends Model
{
    use HasFactory;

    protected $table = 'tbl_tender_dtls';
    protected $primaryKey = 'tender_cd';

    protected $fillable = [
        'tender_cd',
        'tender_title',
        'tender_desc',
        'date_expiry',
        'is_expired',
        'dept_cd',
        'created_by'
    ];
}
