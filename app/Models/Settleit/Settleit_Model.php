<?php

namespace App\Models\Settleit;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  Uuids $id
 * @property  string $status
 * @property  string $case_number
 * @property  string $dispute_details
 * @property  Uuids $plaintiff
 * @property  Uuids $defendant
 * @property  string $currency
 * @property  string $settlement_amount
 */
class Settleit_Model extends Model {
	use HasFactory, Uuids;

	protected $table = 'settleit';
}
