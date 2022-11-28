<?php

namespace App\Models\Settleit;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  Uuids $id
 * @property  string $status
 * @property  string $case_number
 * @property  string $dispute_title
 * @property  string $dispute_details
 * @property  Uuids $creator_id
 * @property  string $creator_role
 * @property  Uuids $plaintiff
 * @property  Uuids $defendant
 * @property  string $currency
 * @property  string $settlement_total_amount
 * @property  string $settlement_amount
 * @property  string $step
 * @property  string $short_id
 * @property  boolean $settleit_show_settlement_amount
 */
class Settleit_Model extends Model {
	use HasFactory;
	use Uuids;

	protected $guarded = [];


	protected $table = 'settleit';

	public function escapeWhenCastingToString($escape = true) {
		// TODO: Implement escapeWhenCastingToString() method.
	}

	public function Settleit_Main_Party() {
		return $this->hasOne(Settleit_Parties_Model::class, 'id', 'creator_id');
	}


	public function Settleit_Recipient_Party() {
		if ($this->creator_role == 'Plaintiff') {
			return $this->hasOne(Settleit_Parties_Model::class, 'id', 'defendant');
		} else {
			return $this->hasOne(Settleit_Parties_Model::class, 'id', 'plaintiff');
		}
	}


}
