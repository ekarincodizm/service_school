<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UtilController\DateUtil;
use Illuminate\Http\Request;
use DB;
use App\Model\StudentAccount;

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

}