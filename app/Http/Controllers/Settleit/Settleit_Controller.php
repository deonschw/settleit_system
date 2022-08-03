<?php

namespace App\Http\Controllers\Settleit;

use App\Http\Controllers\Controller;
use App\Http\Helpers;
use App\Models\ID_Verified\ID_Verified_Model;
use App\Models\Settleit\Settleit_Model;
use App\Models\Settleit\Settleit_Parties_Model;
use App\Models\Settleit\Settleit_Parties_Offer_Data_Model;
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
					'Session_ID' => $Settleit->id,
					'Step'       => $Settleit->step,
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
				'Session_ID'          => [
					'required',
					'exists:settleit,id'
				],
				'Last_Step_Completed' => [
					'required',
					"string"
				],
				'Role'                => [
					'required',
					"string"
				],
				'Case_Number'         => [
					'nullable',
					"string"
				],
				'Dispute_Details'     => [
					'nullable',
					"string"
				],
			]);

			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '1_2';

			if ($request->Case_Number) {
				$Settleit->case_number = $request->Case_Number;
			}
			if ($request->Dispute_Details) {
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
			$Settleit->save();

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
				'Full_Name'           => [
					'required',
					"string"
				],
				'Address'             => [
					'required',
					"string"
				],
				'Mobile_Number'       => [
					'required',
					//					"phone_number" //TODO:: Phone Number validation!!
				],
				'Email_Address'       => [
					'required',
					'email'
				],
				'Device'              => [
					'required',
					'string'
				],
			]);


			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '1_3';

			$Settleit_Parties = Settleit_Parties_Model::where('id', $request->Settleit_Parties_ID)->where('settleit_id', $Settleit->id)->get()->first();

			$Settleit_Parties->full_name = $request->Full_Name;
			$Settleit_Parties->address = $request->Address;
			$Settleit_Parties->mobile_number = $request->Mobile_Number;
			$Settleit_Parties->email_address = $request->Email_Address;
			$Settleit_Parties->Device = $request->Device;
			$Settleit_Parties->save();

			$Settleit->save();

			$Return_Array = array(
				'Session_ID'          => $Settleit->id,
				'Settleit_Parties_ID' => $Settleit_Parties->id,
				'Step'                => $Settleit->step,
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
				'Settleit_Amount'            => [
					'required',
					"string"
				],
				'Settleit_Validation_Period' => [
					'required',
					"string"
				],
			]);

			$Settleit = Settleit_Model::findorfail($request->Session_ID);
			$Settleit->step = '1_5';
			$Settleit->settlement_amount = $request->Settleit_Amount;

			$Settleit_Parties_Offer_Data_Model = new Settleit_Parties_Offer_Data_Model();
			$Settleit_Parties_Offer_Data_Model->settleit_parties_id = $request->Settleit_Parties_ID;
			$Settleit_Parties_Offer_Data_Model->currency = "USD";
			$Settleit_Parties_Offer_Data_Model->amount = $request->Settleit_Amount;
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

	public function Settleit_Step_1_6_Store_Function(Request $request) {
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
					"bool"
				],
			]);


			if ($request->Confirm_And_Send === true) {
				$Settleit = Settleit_Model::findorfail($request->Session_ID);
				$Settleit->step = '1_6';
				$Settleit->status = 'Role 1 Completed - Sending to other party';
				$Settleit->save();
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
}
