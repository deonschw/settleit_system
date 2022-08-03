<?php

use Illuminate\Support\Facades\Log;

//function Get_User_Helper($token) {
//	$User = User::where
//	$api = User\Api_Session::where('Session_ID', $token)->first();
//	if ($api) {
//		return User\User_Model::find($api->User_ID);
//	} else {
//		return false;
//	}
//}

function Response_Successful_Helper($Message, $Data_Key, $Data, $Status_Code = 200) {
	Log::debug('Response_Successful_Helper', array(
		[
			'Error'   => false,
			'Message' => $Message,
			$Data_Key => $Data,
			'Code'    => $Status_Code
		],
	));

	return response([
		'Error'   => false,
		'Message' => $Message,
		$Data_Key => $Data
	], $Status_Code);
}

function Response_Error_Helper($Message, $Status_Code = 200) {
	Log::debug('Response_Error_Helper', array(
		[
			'Error'   => true,
			'Message' => $Message,
			'Code'    => $Status_Code
		],
	));

	return response([
		'Error'   => true,
		'Message' => $Message,
	], $Status_Code);
}

function Response_Error_Helper_With_Data($Message, $Data_Key, $Data, $Status_Code = 200) {
	Log::debug('Response_Error_Helper', array(
		[
			'Error'   => true,
			'Message' => $Message,
			$Data_Key => $Data,
			'Code'    => $Status_Code
		],
	));

	return response([
		'Error'   => true,
		'Message' => $Message,
		$Data_Key => $Data
	], $Status_Code);
}
