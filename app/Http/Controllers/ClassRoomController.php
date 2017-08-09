<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\ClassRoom;
use App\Model\RoomType;
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
			$classRoomDup = ClassRoom::where(
				function ($query) use ($request) {
					$query
					->whereBetween('CR_START_DATE', [$request->startDateString, $request->endDateString])
					->orWhereBetween('CR_END_DATE', [$request->startDateString, $request->endDateString]);
            	}
			)->orWhere(
				function ($query) use ($request)  {
					$query
					->where('CR_START_DATE', '>=', $request->startDateString)
					->where('CR_END_DATE', '<=', $request->endDateString);
            	}
			)->orWhere(
				function ($query) use ($request)  {
					$query
					->where('CR_START_DATE', '<=', $request->startDateString)
					->where('CR_END_DATE', '>=', $request->endDateString);
            	}
			)->where('ROOM_ID', $request->roomId)
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



}