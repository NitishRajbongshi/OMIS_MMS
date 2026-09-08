<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadTrafficIntensityDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'rd_system_id', 
        'intensity_year', 
        'tot_motorized_traffic_per_day', 
        'rd_name', 
        'tot_comm_veh_traffic_per_day', 
        'created_by',
        'updated_by'
    ];

    public function road() {
        $this->belongsTo(AssetRoadDetail::class);
    }
}
