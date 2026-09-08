<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterDeptOfState extends Model
{
    use HasFactory;
    protected $table = 'public.asset_master_dept_of_state';
    protected $fillable = [
        'dept_name',
        'dept_descr'
    ];
}
