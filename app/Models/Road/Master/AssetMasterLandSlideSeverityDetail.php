<?php

namespace App\Models\Road\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterLandSlideSeverityDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'severity_cd',
        'severity_descr'
    ];
}
