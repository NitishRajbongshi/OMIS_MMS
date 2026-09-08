<?php

namespace App\Models\PMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrtProjectSubAssetDetail extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $table = 'projects.prt_project_sub_asset_details';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    protected $fillable = [
        'project_cd',
        'parent_asset_cd',
        'sub_asset_type_cd',
        'sub_asset_sr_no',
        'start_chainage',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];
}
