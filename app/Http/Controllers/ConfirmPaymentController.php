<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UtilController\DateUtil;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Model\StudentAccount;
use App\Model\Bill;
use App\Model\SeqBill;
use App\Model\Room;
use App\Model\RoomType;
use App\Model\BillDetail;
use App\Model\Subject;
use App\Model\ClassRoom;

class ConfirmPaymentController extends Controller{

    public function getIndex() {
		return response ()->json ( [
						'status' => 'ok',
				] );
	}
	
	public function postSearchBill(Request $request) {
		$postdata;
		$billNo;
		try {

			$postdata = file_get_contents("php://input");
			$billNo = json_decode($postdata)->billNo;
			$studentName = json_decode($postdata)->studentName;
			$studentCardId = json_decode($postdata)->studentCardId;

			$bill = BILL::select('BILL.*')
							->where('BILL.USE_FLAG', 'Y')
							->where('BILL.BILL_STATUS', 'W')
							->join('STUDENT_ACCOUNT', 'BILL.SA_ID', '=', 'STUDENT_ACCOUNT.SA_ID');

			if($studentName != ''){
				$bill->whereRaw('CONCAT (STUDENT_ACCOUNT.SA_FIRST_NAME_TH, \' \' ,STUDENT_ACCOUNT.SA_LAST_NAME_TH) LIKE \'%'.$studentName.'%\'');
			}

			if($studentCardId != ''){
				$bill = $bill->where('STUDENT_ACCOUNT.SA_STUDENT_ID', 'LIKE', '%'.$studentCardId.'%');
			}
			
			if($billNo != null){
				$bill = $bill->where('BILL.BILL_NO', 'LIKE', '%'.$billNo.'%');
			}
			
			$bill = $bill->orderBy('BILL.BILL_NO', 'asc')->get();

			$billForms = [];
			
			foreach ($bill as $value) {
				$billForm['bill'] = $value;
				$student = StudentAccount::find($value->SA_ID);

                $billForm['student']['SA_STUDENT_ID'] = $student->SA_STUDENT_ID;
                $billForm['student']['SA_TITLE_NAME_TH'] = $student->SA_TITLE_NAME_TH;
                $billForm['student']['SA_FIRST_NAME_TH'] = $student->SA_FIRST_NAME_TH;
                $billForm['student']['SA_LAST_NAME_TH'] = $student->SA_LAST_NAME_TH;


				if($student->USE_FLAG == "Y"){
					array_push($billForms, $billForm);	
				}

			}
			
			return response()->json($billForms);
			
		} catch ( \Exception $e ) {

			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function postConfirmBill(Request $request) {
		$postdata;
		$bill;
		$billParam;
		$userLoginId;
		try {

			$postdata = file_get_contents("php://input");
			$userLoginId = json_decode($postdata)->userLogin;
			$billParam = json_decode($postdata)->bill;

			DB::beginTransaction();

			$bill = Bill::find($billParam->billId);
			$bill->BILL_STATUS = 'P';
			$bill->RECEIPT_NO = $this->findReceiptNumber();
			$bill->BILL_PAY_DATE = $billParam->billPayDateString;
			$bill->BILL_PAY_REF = $billParam->billPayRef;
			$bill->BILL_PIC = $billParam->billPic;
			$bill->BILL_PIC_TYPE = $billParam->billPicType;
			$bill->UPDATE_DATE = new \DateTime();
			$bill->UPDATE_BY = $userLoginId;
			$bill->save();

			DB::commit(); 
			
			return response ()->json ( [
				'status' => 'ok',
				'billNo' => $bill->BILL_NO,
				'billId' => $bill->BILL_ID
		] );
			
			
		} catch ( \Exception $e ) {
			DB::rollBack ();
			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function findReceiptNumber(){
		try{
			$currentYear = DateUtil::getCurrentThaiYear();
			//$currentMonth = DateUtil::getCurrentMonth2Digit();
			//$currentday = DateUtil::getCurrentDay();
			$seqBill = SeqBill::where('YEAR', '=', $currentYear)->where('INDICATOR', '=', 'R')->first();

			DB::beginTransaction();

			if($seqBill == null){
				$seqBill = new SeqBill();
				$seqBill->RUNNING_NO = 1;
				$seqBill->YEAR = $currentYear;
				$seqBill->INDICATOR = 'R';
				$seqBill->save();
			}else{
				$seqBill->RUNNING_NO = $seqBill->RUNNING_NO+1;
				$seqBill->save();
			}

			DB::commit(); 

			return 'b'.$seqBill->RUNNING_NO.'/'.$currentYear;

		}catch ( \Exception $e ){
			DB::rollBack ();
			throw $e;
		}
	}
    
}