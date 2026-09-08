<?php

namespace App\Models\PMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrtContractorDetail extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
	protected $table = 'prt_contractor_details';
    protected $primaryKey = 'regn_no';
    protected $keyType = 'string';											
    protected $fillable = [
        'regn_no',
        'contractors_name',
        'category_cd',
        'address_line1',
        'address_line2',
        'district_cd',
        'state_cd',
        'phone_no',
        'email',
		'pan_no',
        'bank_acc_no',
        'ifsc_code',
        'is_published',
    ];
}
