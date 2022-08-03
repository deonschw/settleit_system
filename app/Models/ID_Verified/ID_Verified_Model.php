<?php

namespace App\Models\ID_Verified;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @property  Uuids $id
 * @property  Uuids $settleit_parties_id
 * @property  string $id_verified_id
 * @property  boolean $id_confirmed
 * @property  string $data
 */
class ID_Verified_Model extends Model {
	use HasFactory, Uuids;

	protected $table = 'id_verified';
}
