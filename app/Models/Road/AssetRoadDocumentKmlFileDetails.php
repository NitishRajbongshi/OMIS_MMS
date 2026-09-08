<?php

namespace App\Models\Road;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRoadDocumentKmlFileDetails extends Model
{
    use HasFactory;
    protected $table = 'asset_road_document_kml_file_details';
    protected $primaryKey = 'id';

    protected $fillable = [
        'rd_system_id',
        'file_path',
        'file_type',
        'geojson_file_path',
        'created_by',
        'updated_by',
        'project_cd',					 
        'created_at_office_cd'
    ];
}
