<?php

namespace App\Models\PMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrtProjectWorkSubItemsDetail extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $table = 'projects.prt_project_work_sub_items_details';
    protected $fillable = [
        'id',
        'project_cd',
        'item_cd',
        'sub_item_cd',
        'quantity',
        'predecessors_sub_item_codes',
        'est_start_date',
        'est_end_date',
        'is_published',
        'created_by',
        'updated_by',
        'work_item_details_id'
    ];
}
