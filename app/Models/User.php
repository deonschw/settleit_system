<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Settleit\Settleit_Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


/**
 * @property  Uuids $id
 * @property  string $full_name
 * @property  string $email
 * @property  string $mobile_number
 * @property  boolean $account_active
 * @property  boolean $is_super_admin
 * @property  string $country
 * @property  string $password
 */
class User extends Authenticatable {
	use HasApiTokens, HasFactory, Notifiable, Uuids;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'full_name',
		'email',
		'password',
		'mobile_number',
		'account_active',
		'is_super_admin',
		'country',
		'email_verified',
	];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

	public function My_Settleits() {
		return $this->hasMany(Settleit_Model::class, 'id', 'creator_id');
	}
}
