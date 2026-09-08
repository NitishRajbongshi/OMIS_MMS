<?php

namespace App\Models\PMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrtProjectDetail extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $table = 'projects.prt_project_details';
    protected $primaryKey = 'project_cd';
    protected $fillable = [
        'project_cd',
        'project_name',
        'project_type_cd',
        'owner_dept_cd',
        'division_cd',
        'sub_division_cd',
        'parent_asset_cd',
        'project_start_date',
        'project_end_date',
        'est_proj_cost',
        'defect_liability_period',
        'project_status_cd',
        'project_awarded_to',
        'site_eng_id',
        'site_incharge_name',
        'site_incharge_office_cd',
        'site_incharge_ph_no',
        'latitude',
        'longitude',
        'others',
        'is_published',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by',
        'work_order_amount',
        'work_order_no',
        'work_order_issue_date',
        'scheme_cd',
        'funding_agency_cd'
    ];
}
