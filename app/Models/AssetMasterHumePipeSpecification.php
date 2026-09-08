<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterHumePipeSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'hume_pipe_cd',
        'hume_pipe_descr'
    ];
}
