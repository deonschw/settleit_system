<?php

namespace App\Models\Settleit;

use App\Models\Legal\Legal_Data_Model;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  Uuids $id
 * @property  Uuids $settleit_id
 * @property  Uuids $user_id
 * @property  string $role
 * @property  string $full_name
 * @property  string $address
 * @property  string $mobile_number
 * @property  string $email_address
 * @property  boolean $id_verified
 * @property  string $validated_period
 * @property  boolean $is_legal_representative
 * @property  string $device
 */
class Settleit_Parties_Model extends Model {
	use HasFactory, Uuids;

	protected $table = 'settleit_parties';


	public function Settleit_Parties_Settlement_Value() {
		return $this->hasMany(Settleit_Parties_Offer_Data_Model::class, 'settleit_parties_id', 'id')->orderBy('created_at', 'desc');
	}

	public function Settleit_Parties_Lawyer_Details() {
		return $this->hasMany(Legal_Data_Model::class, 'settleit_parties_id', 'id')->orderBy('created_at', 'desc');
	}
}
