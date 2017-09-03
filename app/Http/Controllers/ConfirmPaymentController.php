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

			$bill = BILL::where('USE_FLAG', 'Y')->where('BILL_STATUS', 'W');

			if($billNo != null){
				$bill = $bill->where('BILL_NO', 'LIKE', '%'.$billNo.'%');
			}
			
			$bill = $bill->orderBy('BILL_NO', 'asc')->get();

			$billForms = [];
			
			foreach ($bill as $value) {
				$billForm['bill'] = $value;
				$billForm['student'] = StudentAccount::find($value->SA_ID);
				array_push($billForms, $billForm);	
			}
			
			return response()->json($billForms);
			
		} catch ( \Exception $e ) {

			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}
    
}