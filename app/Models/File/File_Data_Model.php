<?php

namespace App\Models\File;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  Uuids $id
 * @property  Uuids $settleit_parties_id
 * @property  string $file_url
 */
class File_Data_Model extends Model {
	use HasFactory, Uuids;

	protected $table = 'file_data';

}
