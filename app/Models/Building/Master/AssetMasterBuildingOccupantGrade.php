<?php

namespace App\Models\Building\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterBuildingOccupantGrade extends Model
{
    use HasFactory;
    protected $table = 'buildings.asset_master_building_occupant_grade';
    protected $fillable = [
        'grade_cd',
        'grade_descr'
    ];  
}
