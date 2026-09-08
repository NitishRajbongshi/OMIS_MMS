<?php

namespace App\Models\Mechanical;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMechEquipmentDetailHist extends Model
{
    use HasFactory;
    protected $table = 'mechanicals.asset_mech_equipment_details_hist';
    protected $primaryKey = 'id';
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
        'hist_created_by',
        'hist_created_at',
        'hist_remarks'
    ];
}