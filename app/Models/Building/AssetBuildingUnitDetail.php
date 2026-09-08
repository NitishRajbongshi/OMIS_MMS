<?php

namespace App\Models\Building;

use App\Models\Building\Master\AssetMasterBuildingUnitType;
use App\Models\Common\AssetMasterVerificationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetBuildingUnitDetail extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_buildings';
    protected $primaryKey = 'unit_id';
    protected $fillable = [
        'unit_id',
        'building_system_cd',
        'unit_type_cd',
        'unit_name',
        'unit_no',
        'floor_no',
        'plinth_area',
        'has_water_supply',
        'has_electricity',
        'has_sanitary',
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

    public function unitType()
    {
        return $this->belongsTo(AssetMasterBuildingUnitType::class, 'unit_type_cd', 'unit_type_cd');
    }

    public function verificationStatus()
    {
        return $this->belongsTo(AssetMasterVerificationStatus::class, 'status_cd', 'status_cd');
    }
}
