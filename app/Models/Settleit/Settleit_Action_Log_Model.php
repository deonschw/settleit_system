<?php

namespace App\Models\Settleit;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  Uuids $id
 * @property  Uuids $settleit_parties_id
 * @property  string $key
 * @property  string $data
 */
class Settleit_Action_Log_Model extends Model {
	use HasFactory, Uuids;

	protected $table = 'settleit_action_log';
}
