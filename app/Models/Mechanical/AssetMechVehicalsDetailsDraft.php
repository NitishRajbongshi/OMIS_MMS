<?php

namespace App\Models\Mechanical;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMechVehicalsDetailsDraft extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_mechanicals';
    protected $table = 'mechanicals.asset_mech_vehicles_details_draft';
    protected $primaryKey = 'vehicle_asset_cd';
    protected $fillable = [
        'vehicle_asset_cd',
        'vehicle_regn_no',
        'chassis_no',
        'engine_no',
        'vehicle_type',
        'seating_capacity',
        'no_of_wheels',
        'maker',
        'model',
        'fuel_type',
        'date_of_purchase',
        'purchase_cost',
        'vehicle_condition',
        'laden_weight',
        'unladen_weight',
        'vehicle_name',
        'created_at_office_cd',
        'created_by',
        'remarks',
        'alloted_to',
        'alloted_from',
        'sent_for_finalize',
        'sent_for_finalize_on',
        'sent_for_finalize_by',
        'is_rejected',
        'reason_of_rejection',
        'date_of_rejection',
        'rejected_by'
    ];
}
