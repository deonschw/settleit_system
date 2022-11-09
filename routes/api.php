<?php

use App\Http\Controllers\Settleit\Settleit_Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

//TODO:: Add security
Route::prefix('v1')->group(function () {

	Route::post('check_session', [
		Settleit_Controller::class,
		'Check_If_Session_Exists_Function'
	]);

	Route::post('step_1_2', [
		Settleit_Controller::class,
		'Settleit_Step_1_2_Store_Function'
	]);

	Route::post('step_1_3', [
		Settleit_Controller::class,
		'Settleit_Step_1_3_Store_Function'
	]);

	Route::post('step_1_4', [
		Settleit_Controller::class,
		'Settleit_Step_1_4_Store_Function'
	]);

	Route::post('step_1_5', [
		Settleit_Controller::class,
		'Settleit_Step_1_5_Store_Function'
	]);

	Route::post('step_1_6', [
		Settleit_Controller::class,
		'Settleit_Step_1_6_Store_Function'
	]);

	Route::post('step_1_7', [
		Settleit_Controller::class,
		'Settleit_Step_1_7_Store_Function'
	]);

	Route::post('step_1_8', [
		Settleit_Controller::class,
		'Settleit_Step_1_8_Store_Function'
	]);

	Route::post('step_2_1', [
		Settleit_Controller::class,
		'Settleit_Step_2_1_Store_Function'
	]);

	Route::post('step_2_2', [
		Settleit_Controller::class,
		'Settleit_Step_2_2_Store_Function'
	]);

	Route::post('step_2_3', [
		Settleit_Controller::class,
		'Settleit_Step_2_3_Store_Function'
	]);

	Route::post('step_2_4', [
		Settleit_Controller::class,
		'Settleit_Step_2_4_Store_Function'
	]);

	Route::post('step_2_5', [
		Settleit_Controller::class,
		'Settleit_Step_2_5_Store_Function'
	]);

	Route::post('step_2_6', [
		Settleit_Controller::class,
		'Settleit_Step_2_6_Store_Function'
	]);

	Route::post('check_if_user_registered', [
		Settleit_Controller::class,
		'Check_If_User_Registered'
	]);
});
