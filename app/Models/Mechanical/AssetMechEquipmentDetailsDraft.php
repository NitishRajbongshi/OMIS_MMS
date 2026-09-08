<?php

namespace App\Models\Mechanical;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMechEquipmentDetailsDraft extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_mechanicals';
    protected $table = 'mechanicals.asset_mech_equipment_details_draft';
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
        'sent_for_finalize',
        'sent_for_finalize_on',
        'sent_for_finalize_by',
        'chassis_no',
        'eng_no',
        'fuel_type',
        'equipment_type',
        'is_rejected',
        'reason_of_rejection',
        'date_of_rejection',
        'rejected_by'
    ];
}
