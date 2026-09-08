<?php

namespace App\Models\Building;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetBuildingDetailHist extends Model
{
    use HasFactory;

    protected $table = 'buildings.asset_building_details_hist';
    protected $primaryKey = 'id';

    protected $fillable = [
        'building_system_cd',
        'building_type_cd',
        'construction_year',
        'created_by',
        'created_at_office_cd',
        'division_cd',
        'sub_division_cd',
        'asset_name',
        'building_class_cd',
        'bld_qtr_name',
        'qtr_no',
        'bld_catg',
        'plinth_area',
        'construction_cost',
        'has_water_supply',
        'has_electricity',
        'has_sanitary',
        'occupant_name',
        'occupant_dept_cd',
        'remark',
        'building_location_cd',
        'lat',
        'lon',
        'dist_cd',
        'is_maintained_by_npwd',
        'building_access_type_cd',
        'security_fenching_type_cd',
        'asset_owning_dept_cd',
        'plot_area',
        'approved_by',
        'approved_at',
        'asset_plan_id'
    ];
}
