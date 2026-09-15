<?php

namespace App\Models\Maintenance\Inspection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtnInspObservationDetail extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $connection = 'pgsql_maintenance';
    protected $table = 'maintenance.mtn_insp_observation_details';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'insp_id',
        'activity_cd',
        'obsrv_desc',
        'grading_cd',
        'obsrv_weightage',
        'created_by',
        'updated_by',
    ];
}
