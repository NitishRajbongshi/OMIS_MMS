<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasFactory, Notifiable;
	protected $table = 'public.users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phoneno',
        'address1',
        'address2',
        'district',
        'pin',
        'state',
        'country',
        'gender',
        'user_role_id',
        'department',
        'office',
        'designation',
        'post',
        'activity_status',
        'office_type_cd',
        'new_login',
        'secret_code',
        'since_current_position',
        'additional_office_details',
        'qtr_no '
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function assetUserMapping()
    {
        return $this->hasOne(AssetUserMapping::class);
    }

    // gate to check if user is admin
    public function isAdmin(): bool
    {
        return $this->user_role_id === 1;
    }
}
