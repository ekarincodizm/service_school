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
            // $student->SA_TITLE_NAME_EN = $studentForm->studentPrefixEN;
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

			$student->SA_FATHER_TITLE_NAME_TH = $studentForm->fatherPrefix;
            $student->SA_FATHER_NAME = $studentForm->fatherName;
			$student->SA_FATHER_LAST_NAME = $studentForm->fatherLastName;
            $student->SA_FATHER_ADDRESS = $studentForm->fatherAddress;	
			$student->SA_FATHER_PROVINCE = $studentForm->fatherProvince;
			$student->SA_FATHER_AMPHUR = $studentForm->fatherAmphur;
			$student->SA_FATHER_DISTRICT = $studentForm->fatherDistrict;
            $student->SA_FATHER_TEL = $studentForm->fatherTel;

			$student->SA_MOTHER_TITLE_NAME_TH = $studentForm->motherPrefix;
            $student->SA_MOTHER_NAME = $studentForm->motherName;
			$student->SA_MOTHER_LAST_NAME = $studentForm->motherLastName;
            $student->SA_MOTHER_ADDRESS = $studentForm->motherAddress;
			$student->SA_MOTHER_PROVINCE = $studentForm->motherProvince;
			$student->SA_MOTHER_AMPHUR = $studentForm->motherAmphur;
			$student->SA_MOTHER_DISTRICT = $studentForm->motherDistrict;
            $student->SA_MOTHER_TEL = $studentForm->motherTel;
            $student->SA_STUDENT_ID	= $studentId;
			$student->SA_PICTURE	= $studentForm->studentPic;
			$student->SA_PICTURE_TYPE	= (string)$studentForm->studentPicType;

			$student->SA_FATHER_PICTURE	= $studentForm->fatherPic;
			$student->SA_FATHER_PICTURE_TYPE	= (string)$studentForm->fatherPicType;

			$student->SA_MOTHER_PICTURE	= $studentForm->motherPic;
			$student->SA_MOTHER_PICTURE_TYPE	= (string)$studentForm->motherPicType;
			$student->SA_FATHER_PARENT_FLAG = 'N';
			$student->SA_MOTHER_PARENT_FLAG = 'N';
			if($studentForm->fatherParentFlag){
				$student->SA_FATHER_PARENT_FLAG = 'Y';
			}
			if($studentForm->motherParentFlag){
				$student->SA_MOTHER_PARENT_FLAG = 'Y';
			}


			$student->SA_EMERGENCY_TITLE_NAME_TH = $studentForm->emergencyPrefix;
            $student->SA_EMERGENCY_NAME = $studentForm->emergencyName;
			$student->SA_EMERGENCY_LAST_NAME = $studentForm->emergencyLastName;
            $student->SA_EMERGENCY_ADDRESS = $studentForm->emergencyAddress;	
			$student->SA_EMERGENCY_PROVINCE = $studentForm->emergencyProvince;
			$student->SA_EMERGENCY_AMPHUR = $studentForm->emergencyAmphur;
			$student->SA_EMERGENCY_DISTRICT = $studentForm->emergencyDistrict;
            $student->SA_EMERGENCY_TEL = $studentForm->emergencyTel;
			$student->SA_EMERGENCY_PICTURE	= $studentForm->emergencyPic;
			$student->SA_EMERGENCY_PICTURE_TYPE	= (string)$studentForm->emergencyPicType;
			$student->SA_EMERGENCY_PARENT_FLAG = 'N';
			if($studentForm->emergencyParentFlag){
				$student->SA_EMERGENCY_PARENT_FLAG = 'Y';
			}
			$student->CREATE_DATE = new \DateTime();
			$student->CREATE_BY = $userId;
			$student->UPDATE_DATE = new \DateTime();
			$student->UPDATE_BY = $userId;
			$student->save();

			if($studentForm->fatherParentFlag){
				$parent = new StudentParent();
				$parent->SA_ID = $student->SA_ID;
				$parent->SP_TITLE_NAME = $studentForm->fatherPrefix;
				$parent->SP_FIRST_NAME = $studentForm->fatherName;
				$parent->SP_LAST_NAME = $studentForm->fatherLastName;
				$parent->SP_RELATION = 'บิดา';
				$parent->SP_ADDRESS = $studentForm->fatherAddress;
				$parent->SP_PROVINCE = $studentForm->fatherProvince;
				$parent->SP_AMPHUR = $studentForm->fatherAmphur;
				$parent->SP_DISTRICT = $studentForm->fatherDistrict;
				$parent->SP_TEL = $studentForm->fatherTel;
				$parent->SP_PICTURE = $studentForm->fatherPic;
				$parent->SP_PICTURE_TYPE = (string)$studentForm->fatherPicType;
				$parent->SP_RELATION_TYPE = 'D';
				$parent->CREATE_DATE = new \DateTime();
				$parent->CREATE_BY = $userId;
				$parent->UPDATE_DATE = new \DateTime();
				$parent->UPDATE_BY = $userId;
				$parent->save();

			}

			if($studentForm->motherParentFlag){
				$parent = new StudentParent();
				$parent->SA_ID = $student->SA_ID;
				$parent->SP_TITLE_NAME = $studentForm->motherPrefix;
				$parent->SP_FIRST_NAME = $studentForm->motherName;
				$parent->SP_LAST_NAME = $studentForm->motherLastName;
				$parent->SP_RELATION = 'มารดา';
				$parent->SP_ADDRESS = $studentForm->motherAddress;
				$parent->SP_PROVINCE = $studentForm->motherProvince;
				$parent->SP_AMPHUR = $studentForm->motherAmphur;
				$parent->SP_DISTRICT = $studentForm->motherDistrict;
				$parent->SP_TEL = $studentForm->motherTel;
				$parent->SP_PICTURE = $studentForm->motherPic;
				$parent->SP_PICTURE_TYPE = (string)$studentForm->motherPicType;
				$parent->SP_RELATION_TYPE = 'M';
				$parent->CREATE_DATE = new \DateTime();
				$parent->CREATE_BY = $userId;
				$parent->UPDATE_DATE = new \DateTime();
				$parent->UPDATE_BY = $userId;
				$parent->save();

			}

			if($studentForm->emergencyParentFlag){
				$parent = new StudentParent();
				$parent->SA_ID = $student->SA_ID;
				$parent->SP_TITLE_NAME = $studentForm->emergencyPrefix;
				$parent->SP_FIRST_NAME = $studentForm->emergencyName;
				$parent->SP_LAST_NAME = $studentForm->emergencyLastName;
				$parent->SP_RELATION = 'ผู้ติดต่อฉุกเฉิน';
				$parent->SP_ADDRESS = $studentForm->emergencyAddress;
				$parent->SP_PROVINCE = $studentForm->emergencyProvince;
				$parent->SP_AMPHUR = $studentForm->emergencyAmphur;
				$parent->SP_DISTRICT = $studentForm->emergencyDistrict;
				$parent->SP_TEL = $studentForm->emergencyTel;
				$parent->SP_PICTURE = $studentForm->emergencyPic;
				$parent->SP_PICTURE_TYPE = (string)$studentForm->emergencyPicType;
				$parent->SP_RELATION_TYPE = 'E';
				$parent->CREATE_DATE = new \DateTime();
				$parent->CREATE_BY = $userId;
				$parent->UPDATE_DATE = new \DateTime();
				$parent->UPDATE_BY = $userId;
				$parent->save();

			}


			DB::commit(); 
			
			return response ()->json ( [
					'status' => 'ok'
			] );
			
		} catch ( \Exception $e ) {
			DB::rollBack ();
			// echo $e;
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
				$where = $where.' AND (SA_FIRST_NAME_TH LIKE "%'.$request->studentFirstNameTH.'%" OR SA_LAST_NAME_TH LIKE "%'.$request->studentFirstNameTH.'%" )';

			}
			if($request->studentNickNameTH != '' && $request->studentNickNameTH != null){
				$where = $where.' AND SA_NICK_NAME_TH LIKE "%'.$request->studentNickNameTH.'%" ';	

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
            // $student->SA_TITLE_NAME_EN = $studentForm->studentPrefixEN;
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
			
			$student->SA_FATHER_TITLE_NAME_TH = $studentForm->fatherPrefix;
            $student->SA_FATHER_NAME = $studentForm->fatherName;
			$student->SA_FATHER_LAST_NAME = $studentForm->fatherLastName;
            $student->SA_FATHER_ADDRESS = $studentForm->fatherAddress;	
			$student->SA_FATHER_PROVINCE = $studentForm->fatherProvince;
			$student->SA_FATHER_AMPHUR = $studentForm->fatherAmphur;
			$student->SA_FATHER_DISTRICT = $studentForm->fatherDistrict;
            $student->SA_FATHER_TEL = $studentForm->fatherTel;

			$student->SA_MOTHER_TITLE_NAME_TH = $studentForm->motherPrefix;
            $student->SA_MOTHER_NAME = $studentForm->motherName;
			$student->SA_MOTHER_LAST_NAME = $studentForm->motherLastName;
            $student->SA_MOTHER_ADDRESS = $studentForm->motherAddress;
			$student->SA_MOTHER_PROVINCE = $studentForm->motherProvince;
			$student->SA_MOTHER_AMPHUR = $studentForm->motherAmphur;
			$student->SA_MOTHER_DISTRICT = $studentForm->motherDistrict;
            $student->SA_MOTHER_TEL = $studentForm->motherTel;
			$student->SA_PICTURE	= $studentForm->studentPic;
			$student->SA_PICTURE_TYPE	= (string)$studentForm->studentPicType;

			$student->SA_FATHER_PICTURE	= $studentForm->fatherPic;
			$student->SA_FATHER_PICTURE_TYPE	= (string)$studentForm->fatherPicType;

			$student->SA_MOTHER_PICTURE	= $studentForm->motherPic;
			$student->SA_MOTHER_PICTURE_TYPE	= (string)$studentForm->motherPicType;
			$student->SA_FATHER_PARENT_FLAG = 'N';
			$student->SA_MOTHER_PARENT_FLAG = 'N';
			if($studentForm->fatherParentFlag){
				$student->SA_FATHER_PARENT_FLAG = 'Y';
			}
			if($studentForm->motherParentFlag){
				$student->SA_MOTHER_PARENT_FLAG = 'Y';
			}

			$student->SA_EMERGENCY_TITLE_NAME_TH = $studentForm->emergencyPrefix;
            $student->SA_EMERGENCY_NAME = $studentForm->emergencyName;
			$student->SA_EMERGENCY_LAST_NAME = $studentForm->emergencyLastName;
            $student->SA_EMERGENCY_ADDRESS = $studentForm->emergencyAddress;	
			$student->SA_EMERGENCY_PROVINCE = $studentForm->emergencyProvince;
			$student->SA_EMERGENCY_AMPHUR = $studentForm->emergencyAmphur;
			$student->SA_EMERGENCY_DISTRICT = $studentForm->emergencyDistrict;
            $student->SA_EMERGENCY_TEL = $studentForm->emergencyTel;
			$student->SA_EMERGENCY_PICTURE	= $studentForm->emergencyPic;
			$student->SA_EMERGENCY_PICTURE_TYPE	= (string)$studentForm->emergencyPicType;
			$student->SA_EMERGENCY_PARENT_FLAG = 'N';
			if($studentForm->emergencyParentFlag){
				$student->SA_EMERGENCY_PARENT_FLAG = 'Y';
			}


			$student->UPDATE_DATE = new \DateTime();	
			$student->UPDATE_BY = $userId;
			$student->save();
			
			if($studentForm->fatherParentFlag){
			$parentFatherFind = StudentParent::where('SA_ID', $studentForm->studentId)->where('USE_FLAG','Y')->where('SP_RELATION_TYPE','D')->first();
				if($parentFatherFind != null || $parentFatherFind != '' ||$parentFatherFind != []){
					$parentFather = StudentParent::find($parentFatherFind->SP_ID);
					if($parentFather != null || $parentFather != '' ||$parentFather != []){
						$parentFather->SP_TITLE_NAME = $studentForm->fatherPrefix;
						$parentFather->SP_FIRST_NAME = $studentForm->fatherName;
						$parentFather->SP_LAST_NAME = $studentForm->fatherLastName;
						//$parentFather->SP_RELATION = 'บิดา';
						$parentFather->SP_ADDRESS = $studentForm->fatherAddress;
						$parentFather->SP_PROVINCE = $studentForm->fatherProvince;
						$parentFather->SP_AMPHUR = $studentForm->fatherAmphur;
						$parentFather->SP_DISTRICT = $studentForm->fatherDistrict;
						$parentFather->SP_TEL = $studentForm->fatherTel;
						$parentFather->SP_PICTURE = $studentForm->fatherPic;
						$parentFather->SP_PICTURE_TYPE = (string)$studentForm->fatherPicType;
						$parentFather->SP_RELATION_TYPE = 'D';
						$parentFather->UPDATE_DATE = new \DateTime();
						$parentFather->UPDATE_BY = $userId;
						$parentFather->save();
					}
				}else{
					$parent = new StudentParent();
					$parent->SP_TITLE_NAME = $studentForm->fatherPrefix;
					$parent->SP_FIRST_NAME = $studentForm->fatherName;
					$parent->SP_LAST_NAME = $studentForm->fatherLastName;
					$parent->SP_RELATION = 'บิดา';
					$parent->SP_ADDRESS = $studentForm->fatherAddress;
					$parent->SP_PROVINCE = $studentForm->fatherProvince;
					$parent->SP_AMPHUR = $studentForm->fatherAmphur;
					$parent->SP_DISTRICT = $studentForm->fatherDistrict;
					$parent->SP_TEL = $studentForm->fatherTel;
					$parent->SP_PICTURE = $studentForm->fatherPic;
					$parent->SP_PICTURE_TYPE = (string)$studentForm->fatherPicType;
					$parent->SP_RELATION_TYPE = 'D';
					$parent->SA_ID	= $studentForm->studentId;
					$parent->CREATE_DATE = new \DateTime();
					$parent->CREATE_BY = $userId;
					$parent->UPDATE_DATE = new \DateTime();
					$parent->UPDATE_BY = $userId;
					$parent->save();
				}
				

			}else{
					$parentFatherFind = StudentParent::where('SA_ID', $studentForm->studentId)->where('USE_FLAG','Y')->where('SP_RELATION_TYPE','D')->first();
					if($parentFatherFind != null || $parentFatherFind != '' ||$parentFatherFind != []){
						$parentFather = StudentParent::find($parentFatherFind->SP_ID);
						if($parentFather != null || $parentFather != '' ||$parentFather != []){
							$parentFather->USE_FLAG = 'N';
							$parentFather->save();
						}
					}
			}

			if($studentForm->motherParentFlag){
				$parentMotherFind = StudentParent::where('SA_ID', $studentForm->studentId)->where('USE_FLAG','Y')->where('SP_RELATION_TYPE','M')->first();
				if($parentMotherFind != null || $parentMotherFind != '' ||$parentMotherFind != []){
					$parentMother = StudentParent::find($parentMotherFind->SP_ID);
					if($parentMother != null || $parentMother != '' ||$parentMother != []){
						$parentMother = new StudentParent();
						$parentMother->SP_TITLE_NAME = $studentForm->motherPrefix;
						$parentMother->SP_FIRST_NAME = $studentForm->motherName;
						$parentMother->SP_LAST_NAME = $studentForm->motherLastName;
						//$parentMother->SP_RELATION = 'มารดา';
						$parentMother->SP_ADDRESS = $studentForm->motherAddress;
						$parentMother->SP_PROVINCE = $studentForm->motherProvince;
						$parentMother->SP_AMPHUR = $studentForm->motherAmphur;
						$parentMother->SP_DISTRICT = $studentForm->motherDistrict;
						$parentMother->SP_TEL = $studentForm->motherTel;
						$parentMother->SP_PICTURE = $studentForm->motherPic;
						$parentMother->SP_PICTURE_TYPE = (string)$tmp->motherPicType;
						$parentMother->SP_RELATION_TYPE = 'M';
						$parentMother->UPDATE_DATE = new \DateTime();
						$parentMother->UPDATE_BY = $userId;
						$parentMother->save();
					}
				}else{
					$parent = new StudentParent();
					$parent->SP_TITLE_NAME = $studentForm->motherPrefix;
					$parent->SP_FIRST_NAME = $studentForm->motherName;
					$parent->SP_LAST_NAME = $studentForm->motherLastName;
					$parent->SP_RELATION = 'มารดา';
					$parent->SP_ADDRESS = $studentForm->motherAddress;
					$parent->SP_PROVINCE = $studentForm->motherProvince;
					$parent->SP_AMPHUR = $studentForm->motherAmphur;
					$parent->SP_DISTRICT = $studentForm->motherDistrict;
					$parent->SP_TEL = $studentForm->motherTel;
					$parent->SP_PICTURE = $studentForm->motherPic;
					$parent->SP_PICTURE_TYPE = (string)$tmp->motherPicType;
					$parent->SP_RELATION_TYPE = 'M';
					$parent->SA_ID	= $studentForm->studentId;
					$parent->CREATE_DATE = new \DateTime();
					$parent->CREATE_BY = $userId;
					$parent->UPDATE_DATE = new \DateTime();
					$parent->UPDATE_BY = $userId;
					$parent->save();

				}

			}else{
				$parentMotherFind = StudentParent::where('SA_ID', $studentForm->studentId)->where('USE_FLAG','Y')->where('SP_RELATION_TYPE','M')->first();
				if($parentMotherFind != null || $parentMotherFind != '' ||$parentMotherFind != []){
					$parentMother = StudentParent::find($parentMotherFind->SP_ID);
					if($parentMother != null || $parentMother != '' ||$parentMother != []){
						$parentMother->USE_FLAG = 'N';
						$parentMother->save();
					}
				}
			}	

			if($studentForm->emergencyParentFlag){
			$parentEmergencyFind = StudentParent::where('SA_ID', $studentForm->studentId)->where('USE_FLAG','Y')->where('SP_RELATION_TYPE','E')->first();
				if($parentEmergencyFind != null || $parentEmergencyFind != '' ||$parentEmergencyFind != []){
					$parentEmergency = StudentParent::find($parentEmergencyFind->SP_ID);
					if($parentEmergency != null || $parentEmergency != '' ||$parentEmergency != []){
						$parentEmergency->SP_TITLE_NAME = $studentForm->emergencyPrefix;
						$parentEmergency->SP_FIRST_NAME = $studentForm->emergencyName;
						$parentEmergency->SP_LAST_NAME = $studentForm->emergencyLastName;
						$parentEmergency->SP_ADDRESS = $studentForm->emergencyAddress;
						$parentEmergency->SP_PROVINCE = $studentForm->emergencyProvince;
						$parentEmergency->SP_AMPHUR = $studentForm->emergencyAmphur;
						$parentEmergency->SP_DISTRICT = $studentForm->emergencyDistrict;
						$parentEmergency->SP_TEL = $studentForm->emergencyTel;
						$parentEmergency->SP_PICTURE = $studentForm->emergencyPic;
						$parentEmergency->SP_PICTURE_TYPE = (string)$studentForm->emergencyPicType;
						$parentEmergency->SP_RELATION_TYPE = 'D';
						$parentEmergency->UPDATE_DATE = new \DateTime();
						$parentEmergency->UPDATE_BY = $userId;
						$parentEmergency->save();
					}
				}else{
					$parent = new StudentParent();
					$parent->SP_TITLE_NAME = $studentForm->emergencyPrefix;
					$parent->SP_FIRST_NAME = $studentForm->emergencyName;
					$parent->SP_LAST_NAME = $studentForm->emergencyLastName;
					$parent->SP_RELATION = 'ผู้ติดต่อฉุกเฉิน';
					$parent->SP_ADDRESS = $studentForm->emergencyAddress;
					$parent->SP_PROVINCE = $studentForm->emergencyProvince;
					$parent->SP_AMPHUR = $studentForm->emergencyAmphur;
					$parent->SP_DISTRICT = $studentForm->emergencyDistrict;
					$parent->SP_TEL = $studentForm->emergencyTel;
					$parent->SP_PICTURE = $studentForm->emergencyPic;
					$parent->SP_PICTURE_TYPE = (string)$studentForm->emergencyPicType;
					$parent->SP_RELATION_TYPE = 'E';
					$parent->SA_ID	= $studentForm->studentId;
					$parent->CREATE_DATE = new \DateTime();
					$parent->CREATE_BY = $userId;
					$parent->UPDATE_DATE = new \DateTime();
					$parent->UPDATE_BY = $userId;
					$parent->save();
				}
				

			}else{
					$parentEmergencyFind = StudentParent::where('SA_ID', $studentForm->studentId)->where('USE_FLAG','Y')->where('SP_RELATION_TYPE','E')->first();
					if($parentEmergencyFind != null || $parentEmergencyFind != '' ||$parentEmergencyFind != []){
						$parentEmergency = StudentParent::find($parentEmergencyFind->SP_ID);
						if($parentEmergency != null || $parentEmergency != '' ||$parentEmergency != []){
							$parentEmergency->USE_FLAG = 'N';
							$parentEmergency->save();
						}
					}
			}
			
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
