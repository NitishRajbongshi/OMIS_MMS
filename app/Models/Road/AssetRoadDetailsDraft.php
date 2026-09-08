<?php

namespace App\Models\Road;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadDetailsDraft extends Model
{
    use HasFactory;

    protected $table = 'asset_road_details_draft';
    protected $primaryKey = 'rd_system_id';

    protected $fillable = [
        'rd_system_id',
        'rd_category_cd',
        'rd_number',
        'rd_name',
        'rd_type_cd',
        'road_length',
        'rd_owner_cd',
        'created_by',
        'updated_by',
        'road_created_at_office_type',
        'road_created_at_office_cd',
        'road_type',
        'district_name',
        'block_name',
        'lng',
        'lat',
        'division_name',
        'division_cd',
        'block_cd',
        'district_cd',
        'sent_for_finalize',
        'sent_for_finalize_on',
        'sent_for_finalize_by',
        'is_rejected',
        'reason_of_rejection',
        'date_of_rejection',
        'rejected_by',
        'asset_plan_id'//Saiful 18-04-2026
    ];
}
