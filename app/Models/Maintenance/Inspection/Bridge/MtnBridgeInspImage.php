<?php

namespace App\Models\Maintenance\Inspection\Bridge;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtnBridgeInspImage extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $connection = 'pgsql_maintenance';
    protected $table = 'maintenance.mtn_bridge_insp_images';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    protected $fillable = [
        'insp_cd',
        'image_path',
        'file_type',
    ];
}
