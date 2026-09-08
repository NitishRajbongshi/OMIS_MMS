<?php

namespace App\Models\PMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrtProjectSubItemsDetails extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $table = 'projects.prt_project_work_sub_items_details';
    protected $fillable = [
        'project_cd',
        'item_cd',
        'sub_item_cd',
        'quantity',
        'predecessors_sub_item_code',
        'est_start_date',
        'est_end_date',
        'is_published',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];
}
