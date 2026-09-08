<?php

namespace App\Models\Road\Pavement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadPavementShoulderDetail extends Model
{
    use HasFactory;
    protected $primaryKey = 'shoulder_cd';

    protected $fillable = [
        'rd_pavement_cd',
        'shoulder_type_cd',
        'shoulder_start_chainage',
        'shoulder_end_chainage',
        'shoulder_width',
        'created_by',
        'updated_by',
        'created_at_office_cd',
    ];
}
