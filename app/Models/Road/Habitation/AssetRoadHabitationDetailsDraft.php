<?php

namespace App\Models\Road\Habitation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadHabitationDetailsDraft extends Model
{
    use HasFactory;

    protected $table = 'asset_road_habitation_details_draft';
    protected $primaryKey = 'habitation_cd';
    protected $fillable = [
        'habitation_cd',
        'rd_system_id',
        'district_name',
        'block_name',
        'village_name',
        'chainage',
        'administrative_center',
        'market_facility',
        'health_center',
        'educational_institution',
        'list_of_monuments',
        'no_of_intersections',
        'no_of_terrain',
        'no_of_reserve_forest',
        'no_of_sanctuary',
        'no_of_lakes',
        'no_of_tourist_spots',
        'mla_constituency',
        'mp_constituency',
        'total_population',
        'created_by',
        'updated_by',
        'remarks',
        'created_at_ofis_cd',
        'sent_for_finalize',
        'is_rejected',
        'reason_of_rejection',
        'date_of_rejection',
        'rejected_by'
    ];

    public function facilities()
    {
        return $this->hasMany(AssetRoadHabitationFacilityDetailsDraft::class, 'habitation_cd');
    }
}
