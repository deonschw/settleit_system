<?php

namespace App\Http\Controllers\Settleit;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use App\Http\Helpers;
use App\Models\ID_Verified\ID_Verified_Model;
use App\Models\Legal\Legal_Data_Model;
use App\Models\Settleit\Settleit_Model;
use App\Models\Settleit\Settleit_Parties_Model;
use App\Models\Settleit\Settleit_Parties_Offer_Data_Model;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

class Settleit_Controller extends Controller {
	public function Check_If_Session_Exists_Function(Request $request) {
		try {
			if (!isset($request->Session_ID) || !isNull($request->Session_ID)) {
				$Settleit = new Settleit_Model();
				$Settleit->status = 'Session_Started';
				$Settleit->step = '1_1';
				$Settleit->short_id = $this->Settleit_Short_ID_Generator(6);
				$Settleit->save();

				$Return_Array = array(
					'Session_ID' => $Settleit->id,
					'Step'       => $Settleit->step,
				);

				return Response_Successful_Helper('Session Created', 'Data', $Return_Array, 200);
			}

			$request->validate([
				'Session_ID' => [
					'required',
					'exists:settleit,id'
				],
			]);

			$Settleit = Settleit_Model::findorfail($request->Session_ID);


			if ($Settleit->status != 'Complete') {
				$Return_Array = array(
					'Session_ID'      => $Settleit->id,
					'Step'            => $Settleit->step,
					'Settleit_Data'   => $Settleit,
					'Main_Party'      => $Settleit->Settleit_Main_Party,
					'Recipient_Party' => $Settleit->Settleit_Recipient_Party,
				);

			} else {
				$Settleit = new Settleit_Model();
				$Settleit->status = 'Session_Started';
				$Settleit->step = '1_1';
				$Settleit->save();

				$Return_Array = array(
					'Session_ID' => $Settleit->id,
					'Step'       => $Settleit->step,
				);
			}

			return Response_Successful_Helper('Session Continue', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	public function Settleit_Step_1_2_Store_Function(Request $request) {
		try {
			$request->validate([
				'Session_ID'            => [
					'required',
					'exists:settleit,id'
				],
				'Last_Step_Completed'   => [
					'required',
					"string"
				],
				'Role'                  => [
					'required',
					"string"
				],
				'Case_Number'           => [
					'nullable',
					"string"
				],
				'Dispute_Title'         => [
					'nullable',
					"string"
				],
				'Dispute_Details'       => [
					'nullable',
					"string"
				],
				'Settleit_Total_Amount' => [
					'nullable',
					"string"
				],
			]);

			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '1_2';

			if ($request->Case_Number) {
				$Settleit->case_number = $request->Case_Number;
			}
			if ($request->Dispute_Title) {
				$Settleit->dispute_title = $request->Dispute_Title;
				$Settleit->dispute_details = $request->Dispute_Details;
			}

			$Settleit_Parties = Settleit_Parties_Model::where('settleit_id', $Settleit->id)->get()->first();
			if (!$Settleit_Parties) {
				$Settleit_Parties = new Settleit_Parties_Model();
			}

			$Settleit_Parties->settleit_id = $Settleit->id;
			$Settleit_Parties->role = $request->Role;
			$Settleit_Parties->save();

			if ($request->Role == 'Plaintiff') {
				$Settleit->plaintiff = $Settleit_Parties->id;
			} else {
				$Settleit->defendant = $Settleit_Parties->id;
			}

			$Settleit->creator_id = $Settleit_Parties->id;
			$Settleit->creator_role = $request->Role;
			if ($request->Settleit_Total_Amount) {
				$Settleit->settlement_total_amount = $request->Settleit_Total_Amount;
			}
			$Settleit->save();

			if ($request->Settleit_Total_Amount) {
				$Settleit_Parties_Offer_Data_Model = new Settleit_Parties_Offer_Data_Model();
				$Settleit_Parties_Offer_Data_Model->settleit_parties_id = $Settleit_Parties->id;
				$Settleit_Parties_Offer_Data_Model->currency = "USD";
				$Settleit_Parties_Offer_Data_Model->total_amount = $request->Settleit_Total_Amount;
				//				$Settleit_Parties_Offer_Data_Model->settleit_amount = $request->Settleit_Amount;
				$Settleit_Parties_Offer_Data_Model->save();
			}


			$Return_Array = array(
				'Session_ID'          => $Settleit->id,
				'Settleit_Parties_ID' => $Settleit_Parties->id,
				'Step'                => $Settleit->step,
				'Role'                => $request->Role,
			);

			return Response_Successful_Helper('Step 1_2 Complete', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	public function Settleit_Step_1_3_Store_Function(Request $request) {
		try {
			$request->validate([
				'Session_ID'           => [
					'required',
					'exists:settleit,id'
				],
				'Settleit_Parties_ID'  => [
					'required',
					'exists:settleit_parties,id'
				],
				'Last_Step_Completed'  => [
					'required',
					"string"
				],
				'Full_Name'            => [
					'required',
					"string"
				],
				'User_ID'              => [
					'nullable',
					"string"
				],
				'Password'             => [
					'required',
					"string"
				],
				'Mobile_Number'        => [
					'required',
				],
				'Email_Address'        => [
					'required',
					'email'
				],
				'Legal_Representation' => [
					'required',
					'string'
				],
				'Device'               => [
					'required',
					'string'
				],

			]);

			if ($request->get('User_ID') == null || $request->get('User_ID') == 'null' || !$request->has('User_ID')) {
				$Register_Data = array(
					'name'                  => $request->Full_Name,
					'email'                 => $request->Email_Address,
					'mobile_number'         => $request->Mobile_Number,
					'password'              => $request->Password,
					'password_confirmation' => $request->Password,
				);
				$Register_Auth = new RegisterController();
				$Register_Auth->register(new Request($Register_Data));

				$User = User::where('email', $request->Email_Address)->get()->first();

				$User_ID = $User->id;
			} else {
				$User_ID = $request->get('User_ID');
			}

			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '1_3';

			$Settleit_Parties = Settleit_Parties_Model::where('id', $request->Settleit_Parties_ID)->where('settleit_id', $Settleit->id)->get()->first();
			$Settleit_Parties->full_name = $request->Full_Name;
			$Settleit_Parties->user_id = $User_ID;
			$Settleit_Parties->address = $request->Address;
			$Settleit_Parties->mobile_number = $request->Mobile_Number;
			$Settleit_Parties->email_address = $request->Email_Address;
			$Settleit_Parties->is_legal_representative = (bool)$request->Legal_Representation;
			$Settleit_Parties->device = $request->Device;
			$Settleit_Parties->save();

			$Settleit->save();

			$Return_Array = array(
				'Session_ID'          => $Settleit->id,
				'Settleit_Parties_ID' => $Settleit_Parties->id,
				'Step'                => $Settleit->step,
				'User_ID'             => $User_ID,
			);

			return Response_Successful_Helper('Step 1_3 Complete', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	public function Settleit_Step_1_4_Store_Function(Request $request) {
		try {

			$request->validate([
				'Session_ID'          => [
					'required',
					'exists:settleit,id'
				],
				'Settleit_Parties_ID' => [
					'required',
					'exists:settleit_parties,id'
				],
				'Last_Step_Completed' => [
					'required',
					"string"
				],
				'ID_Verified_ID'      => [
					'required',
					"string"
				],
				'ID_Verified_Status'  => [
					'required',
				],
				'ID_Verify_Data'      => [
					'required',
					"string"
					//TODO:: Could need to change to JSON
				],
			]);

			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '1_4';

			$Settleit_Parties = Settleit_Parties_Model::where('id', $request->Settleit_Parties_ID)->where('settleit_id', $Settleit->id)->get()->first();

			$Settleit_Parties->id_verified = $request->ID_Verified_Status;
			$Settleit_Parties->save();

			$ID_Verified_Model = ID_Verified_Model::where('settleit_parties_id', $request->Settleit_Parties_ID)->get()->first();
			if (!$ID_Verified_Model) {
				$ID_Verified_Model = new ID_Verified_Model();
			}

			$ID_Verified_Model->settleit_parties_id = $request->Settleit_Parties_ID;
			$ID_Verified_Model->id_verified_id = $request->ID_Verified_ID;
			$ID_Verified_Model->id_confirmed = $request->ID_Verified_Status;
			$ID_Verified_Model->data = $request->ID_Verify_Data;
			$ID_Verified_Model->save();

			$Settleit->save();

			$Return_Array = array(
				'Session_ID'          => $Settleit->id,
				'Settleit_Parties_ID' => $Settleit_Parties->id,
				'Step'                => $Settleit->step,
			);

			return Response_Successful_Helper('Step 1_4 Complete', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	public function Settleit_Step_1_5_Store_Function(Request $request) {
		try {

			$request->validate([
				'Session_ID'                 => [
					'required',
					'exists:settleit,id'
				],
				'Settleit_Parties_ID'        => [
					'required',
					'exists:settleit_parties,id'
				],
				'Settleit_Total_Amount'      => [
					'required',
					"string"
				],
				'Settleit_Amount'            => [
					'required',
					"string"
				],
				'Settleit_Validation_Period' => [
					'required',
					"string"
				],
				'Settleit_Show_Settlement_Amount' => [
					'nullable',
					"string"
				],
			]);

			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '1_5';
			$Settleit->settlement_amount = $request->Settleit_Amount;
			if($request->has('Settleit_Show_Settlement_Amount')){
				$Settleit->settleit_show_settlement_amount = (bool)$request->Settleit_Show_Settlement_Amount;
			}


			$Settleit_Parties_Offer_Data_Model = new Settleit_Parties_Offer_Data_Model();
			$Settleit_Parties_Offer_Data_Model->settleit_parties_id = $request->Settleit_Parties_ID;
			$Settleit_Parties_Offer_Data_Model->currency = "USD";
			$Settleit_Parties_Offer_Data_Model->total_amount = $request->Settleit_Total_Amount;
			$Settleit_Parties_Offer_Data_Model->settleit_amount = $request->Settleit_Amount;
			if($request->has('Settleit_Show_Settlement_Amount')){
				$Settleit_Parties_Offer_Data_Model->settleit_show_settlement_amount = (bool)$request->Settleit_Show_Settlement_Amount;
			}
			$Settleit_Parties_Offer_Data_Model->save();

			$Settleit->save();

			$Return_Array = array(
				'Session_ID'          => $Settleit->id,
				'Settleit_Parties_ID' => $request->Settleit_Parties_ID,
				'Step'                => $Settleit->step,
			);

			return Response_Successful_Helper('Step 1_5 Complete', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	//TODO:: Part 1 Lawyer Details
	public function Settleit_Step_1_6_Store_Function(Request $request) {
		try {
			$request->validate([
				'Session_ID'           => [
					'required',
					'exists:settleit,id'
				],
				'Settleit_Parties_ID'  => [
					'required',
					'exists:settleit_parties,id'
				],
				'Last_Step_Completed'  => [
					'required',
					"string"
				],
				'Lawyer_Name'          => [
					'required',
					"string"
				],
				'Lawyer_Company_Name'  => [
					'nullable',
					"string"
				],
				'Lawyer_Mobile_Number' => [
					'nullable',
					"string"
				],
				'Lawyer_Email_Address' => [
					'required',
					'email'
				]
			]);


			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '1_6';

			$Settleit_Parties = Settleit_Parties_Model::findorfail($request->Settleit_Parties_ID);

			$Lawyer_Details = Legal_Data_Model::where('settleit_parties_id', $request->Settleit_Parties_ID)->where('settleit_id', $request->Session_ID)->get()->first();
			if ($Lawyer_Details == null) {
				$Lawyer_Details = new Legal_Data_Model();
				$Lawyer_Details->settleit_id = $request->Session_ID;
				$Lawyer_Details->settleit_parties_id = $request->Settleit_Parties_ID;
			}

			$Lawyer_Details->full_name = $request->Lawyer_Name;
			$Lawyer_Details->company_name = $request->Lawyer_Company_Name;
			$Lawyer_Details->mobile_number = $request->Lawyer_Mobile_Number;
			$Lawyer_Details->email_address = $request->Lawyer_Email_Address;
			$Lawyer_Details->save();

			$Return_Array = array(
				'Session_ID'          => $Settleit->id,
				'Settleit_Parties_ID' => $Settleit_Parties->id,
				'Step'                => $Settleit->step,
			);

			return Response_Successful_Helper('Sending Settleit', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	//Note:: Recipiant Details
	public function Settleit_Step_1_7_Store_Function(Request $request) {
		try {
			$request->validate([
				'Session_ID'              => [
					'required',
					'exists:settleit,id'
				],
				'Settleit_Parties_ID'     => [
					'required',
					'exists:settleit_parties,id'
				],
				'Last_Step_Completed'     => [
					'required',
					"string"
				],
				'Recipient_Full_Name'     => [
					'required',
					"string"
				],
				'Recipient_Address'       => [
					'nullable',
					"string"
				],
				'Recipient_Mobile_Number' => [
					'required',
				],
				'Recipient_Email_Address' => [
					'required',
					'email'
				]
			]);


			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '1_7';

			$Settleit_Parties = Settleit_Parties_Model::findorfail($request->Settleit_Parties_ID);
			$Settleit_Recipient_Parties = Settleit_Parties_Model::where('settleit_id', $Settleit->id)->where('id', '!=', $request->Settleit_Parties_ID)->get()->first();
			if (!$Settleit_Recipient_Parties) {
				$Settleit_Recipient_Parties = new Settleit_Parties_Model();
			}

			$Settleit_Recipient_Parties->settleit_id = $Settleit->id;
			if ($Settleit_Parties->role == 'Plaintiff') {
				$Settleit_Recipient_Parties->role = "Defendant";
			} else {
				$Settleit_Recipient_Parties->role = "Plaintiff";
			}
			$Settleit_Recipient_Parties->full_name = $request->Recipient_Full_Name;
			$Settleit_Recipient_Parties->address = $request->Recipient_Address;
			$Settleit_Recipient_Parties->mobile_number = $request->Recipient_Mobile_Number;
			$Settleit_Recipient_Parties->email_address = $request->Recipient_Email_Address;

			$Settleit_Recipient_Parties->save();

			if ($Settleit_Parties->role == 'Plaintiff') {
				$Settleit->defendant = $Settleit_Recipient_Parties->id;
			} else {
				$Settleit->plaintiff = $Settleit_Recipient_Parties->id;
			}
			$Settleit->save();

			$Return_Array = array(
				'Session_ID'                    => $Settleit->id,
				'Settleit_Parties_ID'           => $Settleit_Parties->id,
				'Settleit_Recipient_Parties_ID' => $Settleit_Recipient_Parties->id,
				'Step'                          => $Settleit->step,
			);

			return Response_Successful_Helper('Sending Settleit', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	public function Settleit_Step_1_8_Store_Function(Request $request) {
		try {
			$request->validate([
				'Session_ID'          => [
					'required',
					'exists:settleit,id'
				],
				'Settleit_Parties_ID' => [
					'required',
					'exists:settleit_parties,id'
				],
				'Confirm_And_Send'    => [
					'required',
					"string"
				],
			]);


			if ((bool)$request->Confirm_And_Send === true) {
				$Settleit = Settleit_Model::findorfail($request->Session_ID);
				$Settleit->step = '1_7';
				$Settleit->status = 'Role 1 Completed - Sending to other party';
				$Settleit->save();

				//TODO:: Email Integration to send here.
				//TODO:: Email Integration to send here. - in job update this: $Settleit->status = 'Role 1 Completed - Sending to other party';
				//TODO:: Email Integration to send here.
			}

			$Return_Array = array(
				'Session_ID'          => $Settleit->id,
				'Settleit_Parties_ID' => $request->Settleit_Parties_ID,
				'Step'                => $Settleit->step,
			);

			return Response_Successful_Helper('Sending Settleit', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}


	// Recipient - Get Settleit Details
	public function Settleit_Step_2_1_Store_Function(Request $request) {
		try {
			$request->validate([
				'Session_ID' => [
					'required',
					'exists:settleit,id'
				]
			]);

			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '2_1';
			$Settleit->status = 'Role 2 Opened - Recipient has opened the Settleit';
			$Settleit->save();

			$Settleit_Main_Parties_ID = $Settleit->creator_id;
			if ($Settleit->creator_role == 'Plaintiff') {
				$Settleit_Recipient_Parties_ID = $Settleit->defendant;
			} else {
				$Settleit_Recipient_Parties_ID = $Settleit->plaintiff;
			}

			$Settleit_Main_Parties_Data = Settleit_Parties_Model::findorfail($Settleit_Main_Parties_ID);
			$Settleit_Recipient_Parties_Data = Settleit_Parties_Model::findorfail($Settleit_Recipient_Parties_ID);


			$Return_Array = array(
				'Session_ID'                      => $Settleit->id,
				'Settleit_Main_Parties_ID'        => $Settleit_Main_Parties_ID,
				'Settleit_Recipient_Parties_ID'   => $Settleit_Recipient_Parties_ID,
				'Settleit_Data'                   => $Settleit,
				'Settleit_Main_Parties_Data'      => $Settleit_Main_Parties_Data,
				'Settleit_Recipient_Parties_Data' => $Settleit_Recipient_Parties_Data,
				'Step'                            => $Settleit->step,
			);

			return Response_Successful_Helper('Step 2_1 Complete', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	public function Settleit_Step_2_2_Store_Function(Request $request) {
		try {
			$request->validate([
				'Session_ID'                    => [
					'required',
					'exists:settleit,id'
				],
				'Settleit_Recipient_Parties_ID' => [
					'required',
					'exists:settleit_parties,id'
				],
				'Last_Step_Completed'           => [
					'required',
					"string"
				],
				'Recipient_Full_Name'           => [
					'required',
					"string"
				],
				'Recipient_Address'             => [
					'required',
					"string"
				],
				'Recipient_Mobile_Number'       => [
					'required',
				],
				'Recipient_Email_Address'       => [
					'required',
					'email'
				],
				'Recipient_Device'              => [
					'required',
					'string'
				],
			]);


			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '2_2';
			$Settleit->save();

			$Settleit_Recipient_Parties = Settleit_Parties_Model::where('id', $request->Settleit_Recipient_Parties_ID)->where('settleit_id', $Settleit->id)->get()->first();
			$Settleit_Recipient_Parties->full_name = $request->Recipient_Full_Name;
			$Settleit_Recipient_Parties->address = $request->Recipient_Address;
			$Settleit_Recipient_Parties->mobile_number = $request->Recipient_Mobile_Number;
			$Settleit_Recipient_Parties->email_address = $request->Recipient_Email_Address;
			$Settleit_Recipient_Parties->Device = $request->Recipient_Device;
			$Settleit_Recipient_Parties->save();

			$Return_Array = array(
				'Session_ID'                    => $Settleit->id,
				'Settleit_Recipient_Parties_ID' => $Settleit_Recipient_Parties->id,
				'Step'                          => $Settleit->step,
			);

			return Response_Successful_Helper('Step 2_2 Complete', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	public function Settleit_Step_2_3_Store_Function(Request $request) {
		try {

			$request->validate([
				'Session_ID'                    => [
					'required',
					'exists:settleit,id'
				],
				'Settleit_Recipient_Parties_ID' => [
					'required',
					'exists:settleit_parties,id'
				],
				'Last_Step_Completed'           => [
					'required',
					"string"
				],
				'ID_Verified_ID'                => [
					'required',
					"string"
				],
				'ID_Verified_Status'            => [
					'required',
				],
				'ID_Verify_Data'                => [
					'required',
					"string"
					//TODO:: Could need to change to JSON
				],
			]);

			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '2_3';
			$Settleit->save();

			$Settleit_Recipient_Parties = Settleit_Parties_Model::where('id', $request->Settleit_Recipient_Parties_ID)->where('settleit_id', $Settleit->id)->get()->first();
			$Settleit_Recipient_Parties->id_verified = $request->ID_Verified_Status;
			$Settleit_Recipient_Parties->save();

			$ID_Verified_Model = ID_Verified_Model::where('settleit_parties_id', $request->Settleit_Recipient_Parties_ID)->get()->first();
			if (!$ID_Verified_Model) {
				$ID_Verified_Model = new ID_Verified_Model();
			}

			$ID_Verified_Model->settleit_parties_id = $request->Settleit_Recipient_Parties_ID;
			$ID_Verified_Model->id_verified_id = $request->ID_Verified_ID;
			$ID_Verified_Model->id_confirmed = $request->ID_Verified_Status;
			$ID_Verified_Model->data = $request->ID_Verify_Data;
			$ID_Verified_Model->save();


			$Return_Array = array(
				'Session_ID'          => $Settleit->id,
				'Settleit_Parties_ID' => $Settleit_Recipient_Parties->id,
				'Step'                => $Settleit->step,
			);

			return Response_Successful_Helper('Step 1_4 Complete', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	public function Settleit_Step_2_4_Store_Function(Request $request) {
		try {

			$request->validate([
				'Session_ID'                    => [
					'required',
					'exists:settleit,id'
				],
				'Settleit_Recipient_Parties_ID' => [
					'required',
					'exists:settleit_parties,id'
				],
				'Settleit_Recipient_Amount'     => [
					'required',
					"string"
				],
			]);

			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '2_4';
			$Settleit->save();

			$Settleit_Recipient_Parties_Offer_Data_Model = new Settleit_Parties_Offer_Data_Model();
			$Settleit_Recipient_Parties_Offer_Data_Model->settleit_parties_id = $request->Settleit_Recipient_Parties_ID;
			$Settleit_Recipient_Parties_Offer_Data_Model->currency = "USD";
			$Settleit_Recipient_Parties_Offer_Data_Model->settleit_amount = $request->Settleit_Recipient_Amount;
			$Settleit_Recipient_Parties_Offer_Data_Model->save();

			$Return_Array = array(
				'Session_ID'          => $Settleit->id,
				'Settleit_Parties_ID' => $request->Settleit_Recipient_Parties_ID,
				'Step'                => $Settleit->step,
			);

			return Response_Successful_Helper('Step 2_4 Complete', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	public function Settleit_Step_2_5_Store_Function(Request $request) {
		try {
			$request->validate([
				'Session_ID'           => [
					'required',
					'exists:settleit,id'
				],
				'Settleit_Parties_ID'  => [
					'required',
					'exists:settleit_parties,id'
				],
				'Last_Step_Completed'  => [
					'required',
					"string"
				],
				'Lawyer_Name'          => [
					'required',
					"string"
				],
				'Lawyer_Company_Name'  => [
					'nullable',
					"string"
				],
				'Lawyer_Mobile_Number' => [
					'nullable',
					"string"
				],
				'Lawyer_Email_Address' => [
					'required',
					'email'
				]
			]);


			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '1_6';

			$Settleit_Parties = Settleit_Parties_Model::findorfail($request->Settleit_Parties_ID);

			$Lawyer_Details = Legal_Data_Model::where('settleit_parties_id', $request->Settleit_Parties_ID)->where('settleit_id', $request->Session_ID)->get()->first();
			if ($Lawyer_Details == null) {
				$Lawyer_Details = new Legal_Data_Model();
				$Lawyer_Details->settleit_id = $request->Session_ID;
				$Lawyer_Details->settleit_parties_id = $request->Settleit_Parties_ID;
			}

			$Lawyer_Details->full_name = $request->Lawyer_Name;
			$Lawyer_Details->company_name = $request->Lawyer_Company_Name;
			$Lawyer_Details->mobile_number = $request->Lawyer_Mobile_Number;
			$Lawyer_Details->email_address = $request->Lawyer_Email_Address;
			$Lawyer_Details->save();

			$Return_Array = array(
				'Session_ID'          => $Settleit->id,
				'Settleit_Parties_ID' => $Settleit_Parties->id,
				'Step'                => $Settleit->step,
			);

			return Response_Successful_Helper('Sending Settleit', 'Data', $Return_Array, 200);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	public function Settleit_Step_2_6_Store_Function(Request $request) {
		try {
			$request->validate([
				'Session_ID'                    => [
					'required',
					'exists:settleit,id'
				],
				'Settleit_Recipient_Parties_ID' => [
					'required',
					'exists:settleit_parties,id'
				],
				'Confirm_And_Send'              => [
					'required',
					"bool"
				],
			]);


			if ($request->Confirm_And_Send === true) {
				$Settleit = Settleit_Model::findorfail($request->Session_ID);
				$Settleit->step = '2_5';
				$Settleit->status = 'Role 2 Completed - Submitted an Settlement Back';
				$Settleit->save();

				$Settleit_Match_Function = $this->Settleit_Match_Function($request->Session_ID);
				dd($Settleit_Match_Function);
				//TODO:: If no match - send to screen "no match"

				//TODO:: If match - send notification to both parties - email to both

				//TODO:: If Recipient went bigger then send "save" message


				$Return_Array = array(
					'Session_ID'          => $Settleit->id,
					'Settleit_Parties_ID' => $request->Settleit_Parties_ID,
					'Step'                => $Settleit->step,
				);

				return Response_Successful_Helper('Sending Settleit', 'Data', $Return_Array, 200);

			}


		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}


	private function Settleit_Match_Function($Session_ID) {
		try {
			$Settleit = Settleit_Model::findorfail($Session_ID);

			$Offer_Amount = $Settleit->Settleit_Recipient_Party->Settleit_Parties_Settlement_Value->first()->settleit_amount;

			if ($Settleit->settlement_amount == $Offer_Amount) {
				return array(
					'Error'                     => false,
					'Match'                     => true,
					'Party_1_Settlement_Amount' => $Settleit->settlement_amount,
					'Party_2_Settlement_Amount' => $Offer_Amount,
					'Difference'                => '0'
				);
			}

			if ($Settleit->settlement_amount <= $Offer_Amount) {
				$Difference = (int)$Offer_Amount - (int)$Settleit->settlement_amount;
				return array(
					'Error'                     => false,
					'Match'                     => true,
					'Party_1_Settlement_Amount' => $Settleit->settlement_amount,
					'Party_2_Settlement_Amount' => $Offer_Amount,
					'Difference'                => $Difference
				);
			}

			if ($Settleit->settlement_amount >= $Offer_Amount) {
				return array(
					'Error'                     => false,
					'Match'                     => false,
					'Party_1_Settlement_Amount' => $Settleit->settlement_amount,
					'Party_2_Settlement_Amount' => $Offer_Amount,
					'Difference'                => '0'
				);
			}

			return array(
				'Error'   => true,
				'Message' => "There is an error with the offer data.",
			);
		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	private function Settleit_Short_ID_Generator($Length) {
		$Characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$Characters_Length = strlen($Characters);
		$Random_String = '';
		for ($i = 0; $i < $Length; $i++) {
			$Random_String .= $Characters[rand(0, $Characters_Length - 1)];
		}
		return $Random_String;
	}

	public function Check_If_User_Registered(Request $request) {
		try {
			$request->validate([
				'Session_ID'    => [
					'required',
					'exists:settleit,id'
				],
				'Email_Address' => [
					'required',
					'email'
				],
			]);

			$User = User::where('email', $request->Email_Address)->get()->first();

			if (!$User) {
				$Return_Array = array(
					'Session_ID'      => $request->Session_ID,
					'User_Registered' => false,
					'User_ID'         => null,
				);

			} else {
				$Return_Array = array(
					'Session_ID'      => $request->Session_ID,
					'User_Registered' => true,
					'User_ID'         => $User->id,
				);
			}

			return Response_Successful_Helper('User Registered Check', 'Data', $Return_Array, 200);

		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

	//TODO:: Complete this.
	public function Settleit_Dashboard_Data(Request $request) {
		try {
			$request->validate([
				'Session_ID'    => [
					'required',
					'exists:settleit,id'
				],
				'Email_Address' => [
					'required',
					'email'
				],
			]);

			$User = User::where('email', $request->Email_Address)->get()->first();
			$Return_Array = array(
				'Session_ID'      => $request->Session_ID,
				'User_Registered' => true,
				'User_ID'         => $User->id,
			);

			return Response_Successful_Helper('User Registered Check', 'Data', $Return_Array, 200);

		} catch (Exception $exception) {
			return Response_Error_Helper($exception->getMessage(), 501);
		}
	}

}
