<?php

namespace App\Models\Road;

use App\Models\AssetRoadTrafficIntensityDetail;
use App\Models\Road\CD_Works\AssetRoadCdworkDetail;
use App\Models\Road\CD_Works\AssetRoadCdworkImageDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadDetail extends Model
{
    use HasFactory;

    protected $table = 'asset_road_details';
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
        'approved_by',
        'approved_at',
        'is_road_data_merged_to_all_state_file',
        'is_road_data_merged_to_division_file',
        'state_data_merged_on',
        'division_data_merged_on',
        'asset_plan_id'
    ];

    public function cdWorks()
    {
        return $this->hasMany(AssetRoadCdworkDetail::class);
    }

    public function trafficIntensity()
    {
        return $this->hasMany(AssetRoadTrafficIntensityDetail::class);
    }

    public function culvertImages()
    {
        return $this->hasMany(AssetRoadCdworkImageDetail::class, 'rd_system_id');
    }
}
