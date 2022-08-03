<?php

namespace App\Models\Settleit;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  Uuids $id
 * @property  string $settleit_id
 * @property  string $role
 * @property  string $full_name
 * @property  string $address
 * @property  string $mobile_number
 * @property  string $email_address
 * @property  boolean $id_verified
 * @property  string $validated_period
 * @property  boolean $is_legal_representative
 */
class Settleit_Parties_Model extends Model {
	use HasFactory, Uuids;

	protected $table = 'settleit_parties';
}
