<?php

namespace App\Models\NationalHighway;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assetNhRoadDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'rd_system_id';
    protected $connection = 'pgsql_national_highway';

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
        'road_created_at_office_cd'
    ];
}
