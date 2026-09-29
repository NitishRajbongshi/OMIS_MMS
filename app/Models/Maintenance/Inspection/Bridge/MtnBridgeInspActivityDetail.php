<?php

namespace App\Models\Maintenance\Inspection\Bridge;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtnBridgeInspActivityDetail extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $connection = 'pgsql_maintenance';
    protected $table = 'maintenance.mtn_bridge_insp_activity_details';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'insp_cd',
        'activity_cd',
        'obsrv_desc',
        'grading_cd',
        'obsrv_weightage',
        'remarks',
        'created_by',
        'updated_by',
    ];
}
