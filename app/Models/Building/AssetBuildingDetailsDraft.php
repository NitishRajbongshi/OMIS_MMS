<?php

namespace App\Models\Building;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetBuildingDetailsDraft extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    protected $primaryKey = 'building_system_cd';
    protected $table = 'buildings.asset_building_details_draft';

    protected $fillable = [
        'building_system_cd',
        'house_regn_no',
        'building_type_cd',
        'building_pupose_cd',
        'construction_year',
        'building_alloted_to',
        'alloted_from_year',
        'alloted_from_month',
        'area_covered',
        'land_regn_detail',
        'foundation_type',
        'no_of_storey',
        'boundary_wall_type',
        'date_of_last_renovation',
        'lift_facility',
        'any_other_defects_on_structure',
        'created_by',
        'created_at_office_cd',
        'wall_type',
        'beam_type',
        'column_type',
        'slab_type',
        'staircase_type',
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
        'asset_plan_id'
    ];
}
