<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\ClassRoom;
use App\Model\Room;
use App\Model\RoomType;
use App\Model\BillDetail;
use App\Model\Subject;
use App\Http\Controllers\UtilController\DateUtil;
use DB;

class ClassRoomController extends Controller{

    public function getIndex() {
		return response ()->json ( [
						'status' => 'ok',
				] );
	}

    public function postStoreClassRoom(Request $request) {
		try {

			DB::beginTransaction();

			//Check Duplicate
			$classRoomDup = ClassRoom::whereRaw(
				'((CR_START_DATE BETWEEN '.$request->startDateString.' AND '.$request->endDateString.' 
				 OR CR_END_DATE BETWEEN '.$request->startDateString.' AND '.$request->endDateString.') '.
				'OR (CR_START_DATE >= '.$request->startDateString.' AND CR_END_DATE <= '.$request->endDateString.') '.
				'OR (CR_START_DATE <= '.$request->startDateString.' AND CR_END_DATE >= '.$request->endDateString.')) '
			)
			->where('ROOM_ID', $request->roomId)
			->where('USE_FLAG', 'Y')
			->get();

			if(count($classRoomDup)>0){
				return response ()->json ( [
					'status' => 'warning',
					'warningMessage' => 'ห้องนี้ได้ถูกใช้ในช่วงเวลาที่เลือกแล้ว กรุณาตรวจสอบข้อมูลใหม่อีกครั้ง'
				] );
			}

            $roomType = RoomType::find($request->roomTypeId);

			$classRoom = new ClassRoom();
			$classRoom->SUBJECT_ID = $request->subjectId;
			$classRoom->ROOM_ID = $request->roomId;
            $classRoom->RT_ID = $request->roomTypeId;
            $classRoom->CR_NAME = $request->classRoomName;
            $classRoom->CR_MAX_STUDENT = $request->maxStudent;
            $classRoom->CR_PRICE = $request->price;
           
            if($roomType->RT_GRADE_FLAG == 'G'){
                $classRoom->CR_TERM_FLAG = 'Y';
            }else{
                $classRoom->CR_TERM_FLAG = 'N';
            }

            $classRoom->CR_START_DATE = $request->startDateString;
			$classRoom->CR_END_DATE = $request->endDateString;
			
			//20171026 -- เพิ่ม ภาคเรียนกับปีการศึกษา
			$classRoom->CR_TERM = $request->term;
			$classRoom->CR_YEAR = $request->year;

            $classRoom->CREATE_DATE = new \DateTime();
			$classRoom->CREATE_BY = $request->userLoginId;
			$classRoom->UPDATE_DATE = new \DateTime();
			$classRoom->UPDATE_BY = $request->userLoginId;
			$classRoom->save();

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

	public function postSearchClassRoom(Request $request) {
		try {

			$classRooms = ClassRoom::where('USE_FLAG', 'Y');

			if(!$request->isFirstSearch){

				if(count($request->roomTypeIdSearch) > 0){
					$classRooms->whereIn('RT_ID', $request->roomTypeIdSearch);
				}else{
					$classRooms->where('1','2');
				}

				if($request->roomId != ''){
					$classRooms->where('ROOM_ID', $request->roomId);
				}

				if($request->subjectId != ''){
					$classRooms->where('SUBJECT_ID', $request->subjectId);
				}

				//20171026 -- เพิ่ม ภาคเรียนกับปีการศึกษา
				if($request->term != ''){
					$classRooms->where('CR_TERM', $request->term);
				}

				if($request->year != ''){
					$classRooms->where('CR_YEAR', $request->year);
				}

			}

			$classRooms = $classRooms->get();

			$classRoomForms = [];

			foreach ($classRooms as $classRoom) {
				$classRoomForm['classroom'] = $classRoom;
				$classRoomForm['room'] = Room::find($classRoom->ROOM_ID);
				$classRoomForm['subject'] = Subject::find($classRoom->SUBJECT_ID);
				$classRoomForm['roomType'] = RoomType::find($classRoom->RT_ID);
				$classRoomForm['studentCount'] = BillDetail::where('BILL_DETAIL.CR_ID', $classRoom->CR_ID)
				->leftJoin('BILL', 'BILL_DETAIL.BILL_ID', '=', 'BILL.BILL_ID')
				->where('BILL.BILL_STATUS', '<>' , 'C')
				->count();
				//BillDetail::where('BILL_DETAIL.CR_ID', $classRoom->CR_ID)->leftJoin('BILL', 'BILL_DETAIL.BILL_ID', '=', 'BILL.BILL_ID')->where('BILL_DETAI.USE_FLAG', 'Y')->where('BILL.BILL_STATUS','<>','C')->count();
				array_push($classRoomForms, $classRoomForm);	
			}
			
			
			return response()->json($classRoomForms);
			
		} catch ( \Exception $e ) {

			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function postUpdateClassRoom(Request $request) {
		try {

			DB::beginTransaction();

			//Check Duplicate
			$classRoomDup = ClassRoom::whereRaw(
				'((CR_START_DATE BETWEEN '.$request->startDateString.' AND '.$request->endDateString.' 
				 OR CR_END_DATE BETWEEN '.$request->startDateString.' AND '.$request->endDateString.') '.
				'OR (CR_START_DATE >= '.$request->startDateString.' AND CR_END_DATE <= '.$request->endDateString.') '.
				'OR (CR_START_DATE <= '.$request->startDateString.' AND CR_END_DATE >= '.$request->endDateString.')) '
			)
			->where('CR_ID', '<>', $request->classRoomId)
			->where('ROOM_ID', $request->roomId)
			->where('USE_FLAG', 'Y')
			->get();

			if(count($classRoomDup)>0){
				return response ()->json ( [
					'status' => 'warning',
					'warningMessage' => 'ห้องนี้ได้ถูกใช้ในช่วงเวลาที่เลือกแล้ว กรุณาตรวจสอบข้อมูลใหม่อีกครั้ง'
				] );
			}

            $roomType = RoomType::find($request->roomTypeId);

			$classRoom = ClassRoom::find($request->classRoomId);
			$classRoom->SUBJECT_ID = $request->subjectId;
			$classRoom->ROOM_ID = $request->roomId;
            $classRoom->RT_ID = $request->roomTypeId;
            $classRoom->CR_NAME = $request->classRoomName;
            $classRoom->CR_MAX_STUDENT = $request->maxStudent;
            $classRoom->CR_PRICE = $request->price;
           
            if($roomType->RT_GRADE_FLAG == 'G'){
                $classRoom->CR_TERM_FLAG = 'Y';
            }else{
                $classRoom->CR_TERM_FLAG = 'N';
            }

            $classRoom->CR_START_DATE = $request->startDateString;
			$classRoom->CR_END_DATE = $request->endDateString;
			
			//20171026 -- เพิ่ม ภาคเรียนกับปีการศึกษา
			$classRoom->CR_TERM = $request->term;
			$classRoom->CR_YEAR = $request->year;

            // $classRoom->CREATE_DATE = new \DateTime();
			// $classRoom->CREATE_BY = $request->userLoginId;
			$classRoom->UPDATE_DATE = new \DateTime();
			$classRoom->UPDATE_BY = $request->userLoginId;
			$classRoom->save();

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

	public function postRemoveClassRoom(Request $request) {
		try {

			DB::beginTransaction();
            
			$room = ClassRoom::find($request->classRoomId);
			$room->UPDATE_DATE = new \DateTime();
			$room->UPDATE_BY = $request->userLoginId;
            $room->USE_FLAG = 'N';
			$room->save();

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

}