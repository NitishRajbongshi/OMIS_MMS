<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMasterExpansionJoint extends Model
{
    use HasFactory;

    protected $table = 'asset_master_expansion_joints';
    protected $primaryKey = 'expn_joint_cd';

    protected $fillable = [
        'expn_joint_cd',
        'expn_joint_descr',
    ];
}
