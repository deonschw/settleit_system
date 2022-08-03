<?php

namespace App\Models\Settleit;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  Uuids $id
 * @property  Uuids $settleit_parties_id
 * @property  string $currency
 * @property  string $amount
 */
class Settleit_Parties_Offer_Data_Model extends Model {
	use HasFactory, Uuids;

	protected $table = 'settleit_parties_offer_data';
}
