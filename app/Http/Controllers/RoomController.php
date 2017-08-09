<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\StudentAccount;
use App\Model\StudentParent;
use App\Model\Room;
use App\Model\RoomType;
use DB;
use App\Http\Controllers\UtilController\DateUtil;

class RoomController extends Controller{

    public function getIndex() {
		return response ()->json ( [
						'status' => 'ok',
				] );
	}

    public function postSearchRoom(Request $request) {
		$roomName = null;
		try {

			$roomName = $request->roomName;

			$room = Room::where('USE_FLAG', 'Y');

			if(!is_null($roomName) && $roomName != ''){
				$room = $room->where('ROOM_NAME', 'LIKE', '%'.$roomName.'%');
			}
			
			$room = $room->get();

			return response()->json($room);
			
		} catch ( \Exception $e ) {

			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

     public function postStoreRoom(Request $request) {
		try {

			DB::beginTransaction();

			$room = new Room();
			$room->ROOM_NAME = $request->roomName;
			$room->CREATE_DATE = new \DateTime();
			$room->CREATE_BY = $request->userLoginId;
			$room->UPDATE_DATE = new \DateTime();
			$room->UPDATE_BY = $request->userLoginId;
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

    public function postUpdateRoom(Request $request) {
		try {

			DB::beginTransaction();
            
			$room = Room::find($request->roomId);
			$room->ROOM_NAME = $request->roomName;
			$room->UPDATE_DATE = new \DateTime();
			$room->UPDATE_BY = $request->userLoginId;
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

    public function postRemoveRoom(Request $request) {
		try {

			DB::beginTransaction();
            
			$room = Room::find($request->roomId);
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

	public function postSearchRoomType(Request $request) {
		try {

			$roomType = RoomType::where('USE_FLAG', 'Y')->get();
			return response()->json($roomType);
			
		} catch ( \Exception $e ) {
			DB::rollBack ();
			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}


}