<?php

namespace App\Models\Road\Pavement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadPavementLineDrainageDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'rd_pavement_cd',
        'drainage_side_cd',
        'start_chainage',
        'end_chainage',
        'line_drainage_cd',
        'line_drainage_type_cd',
        'created_by',
        'updated_by',
        'created_at_office_cd',
    ];
}