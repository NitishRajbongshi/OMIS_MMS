<?php

namespace App\Models\Maintenance\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMtnDefectType extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_maintenance';
    protected $table = 'maintenance.master_mtn_defect_types';
    protected $primaryKey = 'defect_type_cd';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['defect_type_cd', 'defect_type_descr'];
}
