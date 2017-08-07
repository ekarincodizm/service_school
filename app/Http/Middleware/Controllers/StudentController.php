<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\StudentAccount;
use App\Model\StudentParent;
use DB;
use App\Http\Controllers\UtilController\DateUtil;

class StudentController extends Controller
{
   public function getIndex() {
	}
	public function postStoreStudent(Request $request) {
		$student;
        $parent;
		try {
			$studentForm = json_decode($request->studentModel);
            $parentForm = json_decode($request->parentModel);
            $year = DateUtil::getCurrentThaiYear2Digit();
            $studentIdOld = StudentAccount::where('SA_STUDENT_ID','LIKE',$year.'%')->max('SA_STUDENT_ID');        
            $studentId =  $studentIdOld+1;
			DB::beginTransaction();
			$student = new StudentAccount();
			$student->SA_TITLE_NAME_TH = $studentForm->studentPrefixTH;
            $student->SA_FIRST_NAME_TH = $studentForm->studentFirstNameTH;
            $student->SA_LAST_NAME_TH = $studentForm->studentLastNameTH;
            $student->SA_NICK_NAME_TH = $studentForm->studentNickNameTH;
            $student->SA_TITLE_NAME_EN = $studentForm->studentPrefixEN;
            $student->SA_FIRST_NAME_EN = $studentForm->studentFirstNameEN;
            $student->SA_LAST_NAME_EN = $studentForm->studentLastNameEN;
            $student->SA_NICK_NAME_EN = $studentForm->studentNickNameEN;
            $student->SA_BIRTH_DATE = $studentForm->birthday;
            $student->SA_NATIONALITY = $studentForm->nationality;
            $student->SA_ETHNIC = $studentForm->ethnic;
            $student->SA_RELIGION = $studentForm->religion;
            $student->SA_PARENT_STATUS = $studentForm->parentStatus;
            $student->SA_FATHER_NAME = $studentForm->fatherName;
            $student->SA_FATHER_ADDRESS = $studentForm->fatherAddress;
            $student->SA_FATHER_TEL = $studentForm->fatherTel;
            $student->SA_MOTHER_NAME = $studentForm->motherName;
            $student->SA_MOTHER_ADDRESS = $studentForm->motherAddress;
            $student->SA_MOTHER_TEL = $studentForm->motherTel;
            $student->SA_STUDENT_ID	= $studentId;
			$student->CREATE_DATE = new \DateTime();
			$student->CREATE_BY = '-';
			$student->UPDATE_DATE = new \DateTime();
			$student->UPDATE_BY = '-';
			$student->save();
			
            $parent = new StudentParent();
            $parent->SA_ID = $student->SA_ID;
            $parent->SP_TITLE_NAME = $parentForm->parentPrefix;
            $parent->SP_FIRST_NAME = $parentForm->parentFirstName;
            $parent->SP_LAST_NAME = $parentForm->parentLastName;
            $parent->SP_RELATION = $parentForm->parentAddress;
            $parent->SP_ADDRESS = $parentForm->parentTel;
            $parent->SP_TEL = $parentForm->relationship;
            $parent->save();


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
	
	public function postSearchStudent(Request $request) {
		$studentName;
		try {
			error_log(1);
			// $studentId = '%'.$request->studentId.'%';
			// $studentName = '%'.$request->studentName.'%';
			
			// $student = Student::where('SA_FIRST_NAME_TH', 'LIKE', $studentName)->where('SA_STUDENT_ID', 'LIKE', $studentId)->where('USE_FLAG', 'Y')->get();
			
			
			// return response()->json($student);

			
		} catch ( \Exception $e ) {
			// return response ()->json ( [
			// 		'status' => 'error',
			// 		'errorDetail' => $e->getMessage()
			// ] );
		}
	}

	public function postUpdateStudent(Request $request) {
		$studentName;
		$studentId;
		try {
			$studentId = $request->studentId;
			$studentName = $request->studentName;
			
			DB::beginTransaction();
			$student = Student::find($studentId);
			$student->SUBJECT_NAME = $studentName;
			$student->UPDATE_DATE = new \DateTime();
 			$student->UPDATE_BY = '-';
			$student->save();
			
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
	
	public function postRemoveStudent(Request $request) {
		$studentId;
		try {
			$studentId = $request->studentId;
			
			DB::beginTransaction();
			$student = Student::find($studentId);
			$student->UPDATE_DATE = new \DateTime();
			$student->UPDATE_BY = '-';
			$student->USE_FLAG = 'N';
			$student->save();
			
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