<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeDetail extends Model
{
    use HasFactory;
	protected $table = 'public.office_details';
    protected $fillable = [
        'office_name',
        'department_id',
        'parent_office',
        'parent_office_id',
        'office_level',
        'office_type_cd',
        'zone_cd',
        'circle_cd',
        'division_cd',
        'sub_division_cd'
    ];
}
