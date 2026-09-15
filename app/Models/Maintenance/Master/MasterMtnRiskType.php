<?php

namespace App\Models\Maintenance\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMtnRiskType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_maintenance';
    protected $table = 'maintenance.master_mtn_risk_types';
    protected $primaryKey = 'risk_type_cd';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['risk_type_cd', 'risk_type_descr'];
}
