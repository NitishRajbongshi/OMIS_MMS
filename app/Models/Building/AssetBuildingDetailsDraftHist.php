<?php

namespace App\Models\Building;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetBuildingDetailsDraftHist extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_buildings';
    protected $table = 'buildings.asset_building_details_draft_hist';
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
        'wall_type',
        'beam_type',
        'column_type',
        'slab_type',
        'staircase_type',
        'boundary_wall_type',
        'date_of_last_renovation',
        'lift_facility',
        'any_other_defects_on_structure',
        'created_by',
        'created_at_office_cd',
        'asset_plan_id'
    ];
}
