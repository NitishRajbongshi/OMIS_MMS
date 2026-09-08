<?php

namespace App\Models\Building;

use App\Models\AssetMasterDivision;
use App\Models\AssetMasterSubDivision;
use App\Models\Building\Master\AssetMasterBuildingAccessType;
use App\Models\Building\Master\AssetMasterBuildingCategory;
use App\Models\Building\Master\AssetMasterBuildingClass;
use App\Models\Building\Master\AssetMasterBuildingLocation;
use App\Models\Building\Master\AssetMasterBuildingSecurityFenchingType;
use App\Models\Building\Master\AssetMasterBuildingType;
use App\Models\OfficeDetail;
use App\Models\Road\Master\AssetMasterDeptOfState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetBuildingDetailDraft extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_buildings';
    protected $primaryKey = 'building_system_cd';
    protected $table = 'buildings.asset_building_details_draft';
    protected $fillable = [
        'building_system_cd',
        'building_type_cd',
        'construction_year',
        'created_by',
        'created_at_office_cd',
        'sent_for_finalize',
        'sent_for_finalize_on',
        'sent_for_finalize_by',
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
        'lon',
        'lat',
        'dist_cd',
        'is_maintained_by_npwd',
        'building_access_type_cd',
        'security_fenching_type_cd',
        'asset_owning_dept_cd',
        'plot_area',
        'rejected_by',
        'is_rejected',
        'reason_of_rejection',
        'date_of_rejection',
        'scheme_cd',
        'floor_type_cd',
        'has_emergency_exit',
        'has_staircase',
        'has_lift',
        'has_ramp',
        'is_pwd_friendly',
        'is_fire_safety_available',
        'last_repaired_cost',
        'last_repaired_scheme_cd',
        'year_of_last_repaired',
        'total_no_of_units',
        'asset_plan_id'
    ];

    /**
     * Building type master  (buildings schema)
     */
    public function buildingType()
    {
        return $this->belongsTo(AssetMasterBuildingType::class, 'building_type_cd', 'building_type_cd');
    }

    /**
     * Building class master  (buildings schema)
     */
    public function buildingClass()
    {
        return $this->belongsTo(AssetMasterBuildingClass::class, 'building_class_cd', 'building_class_cd');
    }

    /**
     * Building category master  (buildings schema)
     */
    public function buildingCategory()
    {
        return $this->belongsTo(AssetMasterBuildingCategory::class, 'bld_catg', 'building_catg_cd');
    }

    /**
     * Building location master  (buildings schema)
     */
    public function buildingLocation()
    {
        return $this->belongsTo(AssetMasterBuildingLocation::class, 'building_location_cd', 'location_cd');
    }

    /**
     * Building access type master  (buildings schema)
     */
    public function accessType()
    {
        return $this->belongsTo(AssetMasterBuildingAccessType::class, 'building_access_type_cd', 'access_type_cd');
    }

    /**
     * Security fencing type master  (buildings schema)
     */
    public function securityFencingType()
    {
        return $this->belongsTo(AssetMasterBuildingSecurityFenchingType::class, 'security_fenching_type_cd', 'fenching_type_cd');
    }

    /**
     * Division master  (public schema)
     */
    public function division()
    {
        return $this->belongsTo(AssetMasterDivision::class, 'division_cd', 'division_cd');
    }

    /**
     * Sub-division master  (public schema)
     */
    public function subDivision()
    {
        return $this->belongsTo(AssetMasterSubDivision::class, 'sub_division_cd', 'sub_div_cd');
    }

    /**
     * Occupant department  (public schema)
     */
    public function occupantDept()
    {
        return $this->belongsTo(AssetMasterDeptOfState::class, 'occupant_dept_cd', 'id');
    }

    /**
     * Asset owning department  (public schema)
     */
    public function owningDept()
    {
        return $this->belongsTo(AssetMasterDeptOfState::class, 'asset_owning_dept_cd', 'id');
    }

    /**
     * Office where record was created  (public schema)
     */
    public function createdAtOffice()
    {
        return $this->belongsTo(OfficeDetail::class, 'created_at_office_cd', 'id');
    }

    /**
     * User who created the record
     */
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }

    /**
     * User who approved the record
     */
    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by', 'id');
    }
}
