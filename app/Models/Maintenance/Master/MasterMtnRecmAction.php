<?php

namespace App\Models\Maintenance\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterMtnRecmAction extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_maintenance';
    protected $table = 'maintenance.master_mtn_recm_actions';
    protected $primaryKey = 'action_type_cd';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['action_type_cd', 'action_type_descr'];
}
