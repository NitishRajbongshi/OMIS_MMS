<?php

namespace App\Models\PMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrtProjectWorkPlanDetails extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $table = 'projects.prt_project_work_plan_details';
    protected $fillable = [
        'project_cd',
        'wp_cd',
        'planned_quantity',
        'unit_cd',
        'plan_start_date',
        'plan_end_date',
        'wid_precedence_item_cd',
        'is_published',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'wid_id',
    ];

    protected $casts = [
        'plan_start_date'       => 'date',
        'plan_end_date'         => 'date',
        'wid_precedence_item_cd'=> 'integer',
        'wid_id'                => 'integer',
    ];

    public function workItem()
    {
        return $this->belongsTo(PrtProjectWorkItemsDetail::class, 'wid_id');
    }
}
