<?php

namespace App\Models\Road\PCI;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadPavementConditionIndexesDraft extends Model
{
    use HasFactory;
    protected $table = 'asset_road_pavement_condition_indexes_draft';
    protected $primaryKey = 'pci_section_cd';
    protected $fillable = [
        'pci_section_cd',
        'pci_section_length_in_meter',
        'rd_system_id',
        'chainage',
        'cracking_percent',
        'ravelling_percent',
        'pot_holes_percent',
        'shoving_percent',
        'patching_percent',
        'settlement_depression_percent',
        'rut_depth',
        'tot_motorized_traffic_per_day',
        'tot_comm_veh_traffic_per_day',
        'pv_traffic_light',
        'pci_value',
        'pci_remarks',
        'created_by',
        'updated_by',
        'created_at_office_cd',
        'sent_for_finalize',
        'pci_year',
        'sent_for_finalize',
        'is_rejected',
        'reason_of_rejection',
        'date_of_rejection',
        'rejected_by'
    ];
}
