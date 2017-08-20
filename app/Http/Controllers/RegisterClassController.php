<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UtilController\DateUtil;
use Illuminate\Http\Request;
use DB;
use App\Model\StudentAccount;
use App\Model\Bill;
use App\Model\BillDetail;

class RegisterClassController extends Controller{

    public function getIndex() {
		return response ()->json ( [
						'status' => 'ok',
				] );
	}

    public function postSearchRegisterClass(Request $request) {
		$studentAccount;
		$student;
		try {

			$student = json_decode($request->student);

			$studentAccount = StudentAccount::where('USE_FLAG', 'Y');

			if($student->studentCardId != ''){
				$studentAccount->where('SA_STUDENT_ID', 'LIKE',  '%'.$student->studentCardId.'%');
			}

			if($student->studentFirstNameTH != ''){
				$studentAccount->where('SA_FIRST_NAME_TH', 'LIKE',  '%'.$student->studentFirstNameTH.'%');
			}

			if($student->studentLastNameTH != ''){
				$studentAccount->where('SA_LAST_NAME_TH', 'LIKE',  '%'.$student->studentLastNameTH.'%');
			}

			$studentAccount = $studentAccount->get();
			
			return response()->json($studentAccount);
			
		} catch ( \Exception $e ) {

			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function postStoreBill(Request $request){
		$postdata;
		$userLoginId;
		$billDetails;
		$studentAccount;
		$billNumber;
		$totalPrice;
		try {
			$postdata = file_get_contents("php://input");
			$userLoginId = json_decode($postdata)->userLogin;
			$billDetails = json_decode($postdata)->billDetails;
			$studentAccount = json_decode($postdata)->student;
			$totalPrice = json_decode($postdata)->totalPrice;
			$billNumber = $this->findBillNumber();

			DB::beginTransaction();

			//Bill
			$bill = new Bill();
			$bill->SA_ID = $studentAccount->studentId;
			$bill->BILL_NO = $billNumber;
			$bill->BILL_STATUS = Bill::BILL_STATUS_WAIT_TO_PAID;
			$bill->BILL_TOTAL_PRICE = $totalPrice;
			$bill->CREATE_DATE = new \DateTime();
			$bill->CREATE_BY = $userLoginId;
			$bill->UPDATE_DATE = new \DateTime();
			$bill->UPDATE_BY = $userLoginId;
			$bill->save();

			//BillDetail
			for ($i = 0; $i < count($billDetails); $i++) {
				 $billDetail = new BillDetail();
				 $billDetail->BILL_ID = $bill->BILL_ID;
				 $billDetail->CR_ID = $billDetails[$i]->billDetail->classRoom->classRoomId;
				 $billDetail->BD_START_LEARN = $billDetails[$i]->billDetail->startLearnString;
				 $billDetail->BD_END_LEARN = $billDetails[$i]->billDetail->endLearnString;
				 $billDetail->BD_PRICE = $billDetails[$i]->billDetail->price;
				 $billDetail->CREATE_DATE = new \DateTime();
				 $billDetail->CREATE_BY = $userLoginId;
				 $billDetail->UPDATE_DATE = new \DateTime();
				 $billDetail->UPDATE_BY = $userLoginId;
				 $billDetail->save();
			}

			DB::commit(); 

			return response ()->json ( [
				'status' => 'ok',
				'billNo' => $billNumber
			] );

		}catch ( \Exception $e ) {
			DB::rollBack ();
			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function postSearchBillHistory(Request $request) {
		$postdata;
		$studentAccountId;
		try {

			$postdata = file_get_contents("php://input");
			$studentAccountId = json_decode($postdata)->studentAccountId;

			$bill = BILL::where('USE_FLAG', 'Y')->where('SA_ID', $studentAccountId)->get();
			
			return response()->json($bill);
			
		} catch ( \Exception $e ) {

			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function findBillNumber(){
		$currentYear = date("Y") + 543;
		$currentMonth = date("m");
		$currentday = date("d");
		$maxBillNo = Bill::max("BILL_NO");

		if($maxBillNo == null){
			$maxBillNo = "01";
		}else{
			$maxBillNo = str_pad((((int) substr($maxBillNo, 7)) + 1),2,"0",STR_PAD_LEFT);
		}

		return $currentYear.$currentMonth.$currentday.$maxBillNo;
	}

	public function postSearchBillDetailByBillid(Request $request){

	}

}