<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterDeckType extends Model
{
    use HasFactory;

    protected $table = 'asset_master_deck_types';
    protected $primaryKey = 'deck_type_cd';

    protected $fillable = [
        'deck_type_cd',
        'deck_type_descr',
    ];
}
