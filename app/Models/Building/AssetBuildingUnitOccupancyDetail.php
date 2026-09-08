<?php

namespace App\Models\Building;

use App\Models\Building\Master\AssetMasterBuildingOccupantGrade;
use App\Models\Common\AssetMasterVerificationStatus;
use App\Models\Road\Master\AssetMasterDeptOfState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetBuildingUnitOccupancyDetail extends Model
{
    use HasFactory;
    protected $connection  = 'pgsql_buildings';
    protected $table       = 'buildings.asset_building_unit_occupancy_details';
    protected $primaryKey  = 'occupancy_id';

    protected $fillable = [
        'unit_id',
        'occupant_name',
        'occupant_dept_cd',
        'occupant_grade_cd',
        'occupied_from',
        'occupied_to',
        'is_current',
        'remarks',
        'status_cd',
        'verified_by',
        'verified_at',
        'rejection_remarks',
        'submitted_by',
        'submitted_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'occupied_from' => 'date',
        'occupied_to'   => 'date',
        'verified_at'   => 'datetime',
        'submitted_at'  => 'datetime',
    ];

    public function unit()
    {
        return $this->belongsTo(AssetBuildingUnitDetail::class, 'unit_id', 'unit_id');
    }

    public function department()
    {
        return $this->belongsTo(AssetMasterDeptOfState::class, 'occupant_dept_cd', 'id');
    }

    public function grade()
    {
        return $this->belongsTo(AssetMasterBuildingOccupantGrade::class, 'occupant_grade_cd', 'grade_cd');
    }

    public function verificationStatus()
    {
        return $this->belongsTo(AssetMasterVerificationStatus::class, 'status_cd', 'status_cd');
    }
}
