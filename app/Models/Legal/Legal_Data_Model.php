<?php

namespace App\Models\Legal;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  Uuids $id
 * @property  Uuids $settleit_id
 * @property  Uuids $settleit_parties_id
 * @property  string $full_name
 * @property  string $address
 * @property  string $mobile_number
 * @property  string $email_address
 * @property  string $company_name
 */
class Legal_Data_Model extends Model {
	use HasFactory, Uuids;

	protected $table = 'legal_data';
}
