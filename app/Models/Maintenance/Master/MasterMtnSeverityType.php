<?php

namespace App\Models\Maintenance\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMtnSeverityType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_maintenance';
    protected $table = 'maintenance.master_mtn_severity_types';
    protected $primaryKey = 'severity_type_cd';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['severity_type_cd', 'severity_type_descr'];
}
