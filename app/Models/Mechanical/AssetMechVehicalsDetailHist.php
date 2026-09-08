<?php

namespace App\Models\Mechanical;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMechVehicalsDetailHist extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_mechanicals';
    protected $table = 'mechanicals.asset_mech_equipment_details_hist';
    protected $primaryKey = 'id';
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
        'hist_created_by',
        'hist_remarks',
        'hist_created_at'
    ];
}
