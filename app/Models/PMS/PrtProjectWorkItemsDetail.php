<?php

namespace App\Models\PMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrtProjectWorkItemsDetail extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $table = 'projects.prt_project_work_items_details';
    protected $fillable = [
        'project_cd',
        'item_cd',
        'quantity',
        'predecessors_item_codes',
        'est_start_date',
        'est_end_date',
        'is_published',
        'created_by',
        'updated_by',
        'item_boq_rate',
        'item_boq_amount'
    ];

    public function prtProjectDetailsDraft()
    {
        return $this->belongsTo(PrtProjectDetailsDraft::class, 'project_cd', 'project_cd');
    }
}
