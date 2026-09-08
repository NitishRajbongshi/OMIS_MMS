<?php

namespace App\Models\PMS\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrmSiteInchargeOfficeDetail extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $table = 'projects.prm_site_incharge_office_details';
    protected $fillable = [
        'office_cd',
        'office_name',
        'contact_person_name',
        'ph_no',
        'address_line1',
        'address_line2',
        'district_cd',
        'state_cd',
        'pin_code',
        'latitude',
        'longitude',
        'is_published'
    ];
}
