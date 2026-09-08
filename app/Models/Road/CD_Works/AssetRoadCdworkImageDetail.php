<?php

namespace App\Models\Road\CD_Works;

use App\Models\Road\AssetRoadDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadCdworkImageDetail extends Model
{
    use HasFactory;
	protected $table = 'asset_road_cdwork_image_details'; //modified by Pulak 02-06-26
    protected $fillable = [
        'rd_cdwork_cd',
        'rd_system_id',
        'image_path',
        'file_type',
        'created_by',
        'updated_by',
        'created_at_office_cd',
        'lat',
        'lon'
    ];

    public function Road()
    {
        return $this->belongsTo(AssetRoadDetail::class, 'rd_system_id');
    }
}
