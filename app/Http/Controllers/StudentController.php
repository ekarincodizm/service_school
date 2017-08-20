<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\StudentAccount;
use App\Model\StudentParent;
use DB;
use App\Http\Controllers\UtilController\DateUtil;
use App\Model\Province;
use App\Model\Amphur;
use App\Model\District;

class StudentController extends Controller
{
   public function getIndex() {
	}
	public function postStoreStudent(Request $request) {
		$student;
        $parent;
		try {
			$postdata = file_get_contents("php://input");
			$studentForm = json_decode($postdata)->studentModel;
            // $parentForms = json_decode($postdata)->parentModel;
			$userId = json_decode($postdata)->userId;
            $year = DateUtil::getCurrentThaiYear2Digit();

            $studentIdOld = StudentAccount::where('SA_STUDENT_ID','LIKE',$year.'%')->max('SA_STUDENT_ID'); 
			if($studentIdOld == null || $studentIdOld == 0){
				$studentId = $year.'00001';
			}else{
				$studentId =  $studentIdOld+1;
			}

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
			$birthday = str_replace("-","",$studentForm->birthday);
			$birthday = substr($birthday,0,4).''.substr($birthday,4,2).''.substr($birthday,6,2);
            $student->SA_BIRTH_DATE = $birthday;
            $student->SA_NATIONALITY = $studentForm->nationality;
            $student->SA_ETHNIC = $studentForm->ethnic;
            $student->SA_RELIGION = $studentForm->religion;
            $student->SA_PARENT_STATUS = $studentForm->parentStatus;
            $student->SA_FATHER_NAME = $studentForm->fatherName;
            $student->SA_FATHER_ADDRESS = $studentForm->fatherAddress;	
			$student->SA_FATHER_PROVINCE = $studentForm->fatherProvince;
			$student->SA_FATHER_AMPHUR = $studentForm->fatherAmphur;
			$student->SA_FATHER_DISTRICT = $studentForm->fatherDistrict;
            $student->SA_FATHER_TEL = $studentForm->fatherTel;
            $student->SA_MOTHER_NAME = $studentForm->motherName;
            $student->SA_MOTHER_ADDRESS = $studentForm->motherAddress;
			$student->SA_MOTHER_PROVINCE = $studentForm->motherProvince;
			$student->SA_MOTHER_AMPHUR = $studentForm->motherAmphur;
			$student->SA_MOTHER_DISTRICT = $studentForm->motherDistrict;
            $student->SA_MOTHER_TEL = $studentForm->motherTel;
            $student->SA_STUDENT_ID	= $studentId;
			$student->SA_PICTURE	= $studentForm->studentPic;
			$student->SA_PICTURE_TYPE	= (string)$studentForm->studentPicType;
			$student->CREATE_DATE = new \DateTime();
			$student->CREATE_BY = $userId;
			$student->UPDATE_DATE = new \DateTime();
			$student->UPDATE_BY = $userId;
			$student->save();
			// foreach ($parentForms as $parentForm) {
			// 	$tmp = $parentForm;
			// 	$parent = new StudentParent();
			// 	$parent->SA_ID = $student->SA_ID;
			// 	$parent->SP_TITLE_NAME = $tmp->parentPrefix;
			// 	$parent->SP_FIRST_NAME = $tmp->parentFirstName;
			// 	$parent->SP_LAST_NAME = $tmp->parentLastName;
			// 	$parent->SP_RELATION = $tmp->relationship;
			// 	$parent->SP_ADDRESS = $tmp->parentAddress;
			// 	$parent->SP_PROVINCE = $tmp->parentProvince;
			// 	$parent->SP_AMPHUR = $tmp->parentAmphur;
			// 	$parent->SP_DISTRICT = $tmp->parentDistrict;
			// 	$parent->SP_TEL = $tmp->parentTel;
			// 	$parent->SP_PICTURE = $tmp->parentPic;
			// 	$parent->SP_PICTURE_TYPE = (string)$tmp->parentPicType;
			// 	$parent->CREATE_DATE = new \DateTime();
			// 	$parent->CREATE_BY = $userId;
			// 	$parent->UPDATE_DATE = new \DateTime();
			// 	$parent->UPDATE_BY = $userId;
			// 	$parent->save();
			// }


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
	
	public function postSearchParent(Request $request) {
		try {
			$studentId = $request->studentId;
			$parents = StudentParent::where('SA_ID',$studentId)->where('USE_FLAG' , 'Y')->get();
			$addressList;
			$postCodeList;
			foreach ($parents as $key=> $parent){
				$province = Province::find($parents[$key]->SP_PROVINCE);
				$amphur = Amphur::find($parents[$key]->SP_AMPHUR);
				$district = District::find($parents[$key]->SP_DISTRICT);
				$address = 'ต.'.$district->DISTRICT_NAME.' อ.'.$amphur->AMPHUR_NAME.' จ.'.$province->PROVINCE_NAME.' '.$amphur->POSTCODE;
				$addressList[$key] = $address;
				$postCodeList[$key] = $amphur->POSTCODE;
			}
			return response ()->json ( [
					'status' => 'ok',
					'parents' => $parents,
					'address' => $addressList,
					'postCode' => $postCodeList,
			] );

			
		} catch ( \Exception $e ) {
			error_log($e);
			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function postSearchStudent(Request $request) {
		$studentId = '';
        $studentName = '';
		$where = ' where a.USE_FLAG = "Y" ';
		try {
			
			if($request->studentCardId != '' && $request->studentCardId != null){
				$where = $where.' AND SA_STUDENT_ID LIKE "%'.$request->studentCardId.'%" ';	

			}
			if($request->studentFirstNameTH != ''&& $request->studentFirstNameTH != null){
				$where = $where.' AND SA_FIRST_NAME_TH LIKE "%'.$request->studentFirstNameTH.'%" ';

			}
			// $student = StudentAccount::where('SA_FIRST_NAME_TH', 'LIKE', $studentName)->where('SA_STUDENT_ID', 'LIKE', $studentId)->where('USE_FLAG', 'Y')->get();
			$student = DB::select('SELECT * from STUDENT_ACCOUNT a '.$where .'
									GROUP BY a.SA_ID');
			return response()->json($student);

			
		} catch ( \Exception $e ) {
			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function postUpdateStudent(Request $request) {
		try {
			
			
			$postdata = file_get_contents("php://input");
			$studentForm = json_decode($postdata)->studentModel;
            // $parentForms = json_decode($postdata)->parentModel;
			$userId = json_decode($postdata)->userId;
			
			DB::beginTransaction();
			$student = StudentAccount::find($studentForm->studentId);
			$student->SA_TITLE_NAME_TH = $studentForm->studentPrefixTH;
            $student->SA_FIRST_NAME_TH = $studentForm->studentFirstNameTH;
            $student->SA_LAST_NAME_TH = $studentForm->studentLastNameTH;
            $student->SA_NICK_NAME_TH = $studentForm->studentNickNameTH;
            $student->SA_TITLE_NAME_EN = $studentForm->studentPrefixEN;
            $student->SA_FIRST_NAME_EN = $studentForm->studentFirstNameEN;
            $student->SA_LAST_NAME_EN = $studentForm->studentLastNameEN;
            $student->SA_NICK_NAME_EN = $studentForm->studentNickNameEN;
			$birthday = str_replace("-","",$studentForm->birthday);
			$birthday = substr($birthday,0,4).''.substr($birthday,4,2).''.substr($birthday,6,2);
            $student->SA_BIRTH_DATE = $birthday;
            $student->SA_NATIONALITY = $studentForm->nationality;
            $student->SA_ETHNIC = $studentForm->ethnic;
            $student->SA_RELIGION = $studentForm->religion;
            $student->SA_PARENT_STATUS = $studentForm->parentStatus;
            $student->SA_FATHER_NAME = $studentForm->fatherName;
			
			$student->SA_FATHER_ADDRESS = $studentForm->fatherAddress;
			$student->SA_FATHER_PROVINCE = $studentForm->fatherProvince;
			$student->SA_FATHER_AMPHUR = $studentForm->fatherAmphur;
			$student->SA_FATHER_DISTRICT = $studentForm->fatherDistrict;
            $student->SA_FATHER_TEL = $studentForm->fatherTel;
            $student->SA_MOTHER_NAME = $studentForm->motherName;
            $student->SA_MOTHER_ADDRESS = $studentForm->motherAddress;
			$student->SA_MOTHER_PROVINCE = $studentForm->motherProvince;
			$student->SA_MOTHER_AMPHUR = $studentForm->motherAmphur;
			$student->SA_MOTHER_DISTRICT = $studentForm->motherDistrict;

            $student->SA_MOTHER_TEL = $studentForm->motherTel;
			$student->SA_PICTURE	= $studentForm->studentPic;	
			$student->SA_PICTURE_TYPE	= (string)$studentForm->studentPicType;

			$student->UPDATE_DATE = new \DateTime();	
			$student->UPDATE_BY = $userId;
			$student->save();
			// $tmpList = array();
			// foreach ($parentForms as $parentForm) {
			// 	$tmp = $parentForm;
			// 	$tmpId = $tmp->parentId;
			// 	if($tmpId != null && $tmpId != ''){
			// 		array_push($tmpList, $tmpId);
			// 	}
				
			// }

			// StudentParent::where('SA_ID',$student->SA_ID)->whereNotIn('SP_ID', $tmpList)->update(['USE_FLAG' => 'N','UPDATE_BY'=>$request->userLoginId,'UPDATE_DATE'=>new \DateTime()]);

			// foreach ($parentForms as $parentForm) {
			// 	$tmp = $parentForm;
			// 	$tmpId = $tmp->parentId;
			// 	if($tmpId != null && $tmpId != ''){
			// 		$parent = StudentParent::find($tmpId);
			// 	}else{
			// 		$parent = new StudentParent();
			// 	}
				
			// 	$parent->SA_ID = $student->SA_ID;
			// 	$parent->SP_TITLE_NAME = $tmp->parentPrefix;
			// 	$parent->SP_FIRST_NAME = $tmp->parentFirstName;
			// 	$parent->SP_LAST_NAME = $tmp->parentLastName;
			// 	$parent->SP_RELATION = $tmp->relationship;
			// 	$parent->SP_ADDRESS = $tmp->parentAddress;
			// 	$parent->SP_PROVINCE = $tmp->parentProvince;
			// 	$parent->SP_AMPHUR = $tmp->parentAmphur;
			// 	$parent->SP_DISTRICT = $tmp->parentDistrict;
			// 	$parent->SP_TEL = $tmp->parentTel;
			// 	$parent->SP_PICTURE = $tmp->parentPic;
			// 	$parent->SP_PICTURE_TYPE = (string)$tmp->parentPicType;
			// 	$parent->UPDATE_DATE = new \DateTime();
			// 	$parent->UPDATE_BY = $userId;
			// 	$parent->save();
			// }
			
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
			// $studentForm = json_decode($request->studentModel);
			
			DB::beginTransaction();
			$student = StudentAccount::find($request->studentId);
			$student->UPDATE_DATE = new \DateTime();
			$student->UPDATE_BY = $request->userLoginId;
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
					'errorDetail' => $request->userLoginId
			] );
		}
	}
}
