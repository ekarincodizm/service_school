<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UtilController\DateUtil;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Model\StudentAccount;
use App\Model\Bill;
use App\Model\Room;
use App\Model\RoomType;
use App\Model\BillDetail;
use App\Model\Subject;
use App\Model\ClassRoom;

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

			$bill = BILL::where('USE_FLAG', 'Y')->where('SA_ID', $studentAccountId)->orderBy('BILL_NO', 'desc')->get();
			
			return response()->json($bill);
			
		} catch ( \Exception $e ) {

			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function findBillNumber(){
		$currentYear = DateUtil::getCurrentThaiYear();
		$currentMonth = DateUtil::getCurrentMonth2Digit();
		$currentday = DateUtil::getCurrentDay();
		$maxBillNo = Bill::max("BILL_NO");

		if($maxBillNo == null){
			$maxBillNo = "0001";
		}else{
			$maxBillNo = str_pad((((int) substr($maxBillNo, 6)) + 1),4,"0",STR_PAD_LEFT);
		}

		return $currentYear.$currentMonth.$maxBillNo;
	}

	public function postSearchBillDetailByBillid(Request $request){
		$postdata;
		$billId;
		try {

			$postdata = file_get_contents("php://input");
			$billId = json_decode($postdata)->billId;

			$billDetails = BillDetail::where('BILL_ID', $billId)->get();

			$registerClasses = [];
			foreach($billDetails as $billDetail){
				$registerClass['billDetail'] = $billDetail;
				$registerClass['classRoom'] = ClassRoom::find($billDetail->CR_ID);
				$registerClass['room'] = Room::find($registerClass['classRoom']->ROOM_ID);
				$registerClass['subject'] = Subject::find($registerClass['classRoom']->SUBJECT_ID);
				$registerClass['roomType'] = RoomType::find($registerClass['classRoom']->RT_ID);
				array_push($registerClasses, $registerClass);
			}
			
			return response()->json($registerClasses);
			
		} catch ( \Exception $e ) {

			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function postCancleBill(Request $request){
		$postdata;
		$billId;
		$userLogin;
		try {

			$postdata = file_get_contents("php://input");
			$billId = json_decode($postdata)->billId;
			$userLoginId = json_decode($postdata)->userLoginId;

			DB::beginTransaction();

			$bill = Bill::find($billId);
			$bill->BILL_STATUS = Bill::BILL_STATUS_CANCLE;
			$bill->UPDATE_DATE = new \DateTime();
			$bill->UPDATE_BY = $userLoginId;
			$bill->save();
			DB::commit(); 
			
			return response ()->json ( [
				'status' => 'ok'
			] );
			
		} catch ( \Exception $e ) {
			DB::rollBack ();
			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function getCountCurrentStudentClassRoom($startDateString, $endDateString, $classRoomId){
		//echo $startDate." ".$endDate." ".$classRoomId;
		$classRoom;
		$roomType;
		$billDetail;
		$startDateCarbon;
		$endDateCarbon;
		$diffDate;
		$countStudent = 0;
		try {
			//หาคลาสก่อนเพื่อจะหาจำนวนเด็กที่สามารถลงได้ และระดับของห้องเรียน
			$classRoom = ClassRoom::find($classRoomId);
			$roomType = RoomType::find($classRoom->RT_ID);

			if($roomType->RT_GRADE_FLAG == 'P'){
				//ถ้าเป็นเตรียมอนุบาลจะต้องคำนวนวันทุกวัน เพื่อตรวจสอบว่าเต็มหรือยัง
				$startDateCarbon = DateUtil::convertDbToDate($startDateString);
				$endDateCarbon = DateUtil::convertDbToDate($endDateString);
				$diffDate = DateUtil::countDaysBetweenDate($startDateCarbon, $endDateCarbon);

				for ($i = 1; $i <= $diffDate; $i++) {

					$dateString = DateUtil::getDisplaytoStore(DateUtil::addDate($startDateCarbon, 1));

					$billDetail = BillDetail::where('BILL_DETAIL.CR_ID', $classRoom->CR_ID)
					->leftJoin('BILL', 'BILL_DETAIL.BILL_ID', '=', 'BILL.BILL_ID')
					->where('BILL.BILL_STATUS', '<>' , 'C')
					->where('BILL_DETAIL.BD_START_LEARN', '<=' , $dateString)
					->where('BILL_DETAIL.BD_END_LEARN', '>=' , $dateString)
					->get();

					$countStudentLoop = count($billDetail);

					if($countStudent < $countStudentLoop){
						$countStudent = $countStudentLoop;
					}
					
				}
			
			}else{
				//ถ้าเป็นอนุบาล count โดยตรงได้เลย
				$billDetail = BillDetail::where('BILL_DETAIL.CR_ID', $classRoom->CR_ID)
								->leftJoin('BILL', 'BILL_DETAIL.BILL_ID', '=', 'BILL.BILL_ID')
								->where('BILL.BILL_STATUS', '<>' , 'C')
								->get();
				$countStudent = count($billDetail);
			}

			return response ()->json ( [
				'status' => 'ok',
				'totalStudent' => $countStudent
		] );

		}catch ( \Exception $e ) {
			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}

	}

}