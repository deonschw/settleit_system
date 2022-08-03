<?php

namespace App\Http\Controllers\Settleit;

use App\Http\Controllers\Controller;
use App\Models\Settleit\Settleit_Model;
use App\Models\User;
use Illuminate\Http\Request;

class Admin_Controller extends Controller {
	public function Dashboard(Request $request) {

		$Settleit = Settleit_Model::orderBy('created_at', 'desc')->get();
		$Settleit_Competed_Successfully = Settleit_Model::where('status', 'Completed')->get()->count();
		$Users = User::orderBy('created_at', 'desc')->get();

		return view('admin.dashboard.dashboard', [
			'Users'                          => $Users->take(20),
			'User_Count'                     => $Users->count(),
			'Settleit'                       => $Settleit->take(20),
			'Settleit_Count'                 => $Settleit->count(),
			'Settleit_Competed_Successfully' => $Settleit_Competed_Successfully,
		]);

	}
}
