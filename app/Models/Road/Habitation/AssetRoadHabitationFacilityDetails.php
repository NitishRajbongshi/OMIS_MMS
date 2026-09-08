<?php

namespace App\Models\Road\Habitation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadHabitationFacilityDetails extends Model
{
    use HasFactory;

    protected $table = 'asset_road_habitation_facility_details';
    protected $primaryKey = 'id';


    public function facility()
    {
        return $this->belongsTo(AssetMasterHabitationFacilities::class, 'id');
    }

    public function subFacility()
    {
        return $this->belongsTo(AssetMasterHabitationSubFacilities::class, 'id');
    }
}
