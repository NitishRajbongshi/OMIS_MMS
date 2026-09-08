<?php

namespace App\Models\Mechanical;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMechEquipmentDetail extends Model
{
    use HasFactory;
    protected $table = 'mechanicals.asset_mech_equipment_details';
    protected $primaryKey = 'euipment_cd';
    protected $fillable = [
        'euipment_cd',
        'equipment_name',
        'serial_number',
        'model_no',
        'purchase_year',
        'purchase_cost',
        'equipment_condition_cd',
        'is_under_waranty',
        'created_at_office_cd',
        'equipment_remarks',
        'created_by',
        'chassis_no',
        'eng_no',
        'fuel_type',
        'equipment_type',
        'approved_by',
        'approved_at'
    ];
}
