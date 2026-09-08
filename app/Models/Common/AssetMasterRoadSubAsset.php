<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterRoadSubAsset extends Model
{
    use HasFactory;
    protected $primaryKey = 'sub_asset_cd';
    protected $fillable = [
        'sub_asset_cd',
        'sub_asset_descr',
        'maker_checker_enabled'
    ];

    public static function getMakerCheckerStatus($subAssetCd)
    {
        return self::where('sub_asset_cd', $subAssetCd)->value('maker_checker_enabled');
    }
}
