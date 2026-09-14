<?php

namespace App\Models\Maintenance\Inspection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtnInspectionDetail extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $connection = 'pgsql_maintenance';
    protected $table = 'maintenance.mtn_inspection_details';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'insp_cd',
        'insp_date',
        'insp_type_cd',
        'inspector_name',
        'inspector_designation',
        'insp_asset_type',
        'insp_asset_id',
        'insp_asset_location',
        'start_section',
        'end_section',
        'cndtn_overall',
        'cndtn_pavement',
        'cndtn_drainage',
        'cndtn_shoulder',
        'cndtn_structural',
        'cndtn_safety_features',
        'cndtn_signage',
        'observation_descr',
        'risk_type_cd',
        'recmnd_action_type_cd',
        'recmnd_priority',
        'recmnd_work',
        'is_defects_observed',
        'pci_section_cd',
        'surface_type_id',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'insp_date' => 'datetime',
        'start_section' => 'decimal:3',
        'end_section' => 'decimal:3',
        'surface_type_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];
}
