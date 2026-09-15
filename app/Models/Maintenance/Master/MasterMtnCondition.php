<?php

namespace App\Models\Maintenance\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMtnCondition extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_maintenance';
    protected $table = 'maintenance.master_mtn_conditions';
    protected $primaryKey = 'condition_type_cd';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['condition_type_cd', 'condition_type_descr'];
}
