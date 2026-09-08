<?php

namespace App\Models\PMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrtProjectIowBoqMapping extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $table = 'prt_project_iow_boq_mapping';
    protected $primaryKey = 'iow_id';
    public $incrementing = false;   // IMPORTANT
    protected $keyType = 'string';  // IMPORTANT

    protected $fillable =[
        'iow_id',
        'boq_item_id',
        'created_by',
        'updated_by'
    ];
}
