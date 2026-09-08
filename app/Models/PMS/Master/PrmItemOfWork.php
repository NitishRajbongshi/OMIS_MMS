<?php

namespace App\Models\PMS\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrmItemOfWork extends Model
{
    use HasFactory;
    protected $connection = 'pgsql_pms';
    protected $table = 'projects.prm_item_of_work';
    protected $fillable = [
        'item_cd',
        'item_name',
        'dept_cd',
        'is_published'
    ];
}
