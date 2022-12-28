<?php

namespace App\Models\App_Env;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  Uuids $id
 * @property  string $key
 * @property  string $data
 */
class App_Env_Model extends Model {
	use HasFactory, Uuids;

	protected $table = 'app_env_data';
}
