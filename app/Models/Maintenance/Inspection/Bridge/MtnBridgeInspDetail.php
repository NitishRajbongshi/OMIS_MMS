<?php

namespace App\Models\Maintenance\Inspection\Bridge;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtnBridgeInspDetail extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $connection = 'pgsql_maintenance';
    protected $table = 'maintenance.mtn_bridge_insp_details';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'insp_cd',
        'insp_date',
        'inspector_name',
        'inspector_designation',
        'rd_system_id',
        'rd_bridge_cd',
        'bridge_condtn',
        'remarks',
        'created_by',
        'updated_by',
    ];
}
