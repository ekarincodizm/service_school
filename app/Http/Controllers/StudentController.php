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

            // $studentIdOld = StudentAccount::where('SA_STUDENT_ID','LIKE',$year.'%')->max('SA_STUDENT_ID'); 
			// if($studentIdOld == null || $studentIdOld == 0){
			// 	$studentId = $year.'00001';
			// }else{
			// 	$studentId =  $studentIdOld+1;
			// }
			
			$studentCheck = StudentAccount::where('USE_FLAG','Y')->where('SA_STUDENT_ID',$studentForm->studentCardId)->first();
			if($studentCheck != null || $studentCheck != '' ||$studentCheck != []){
				return response ()->json ( [
					'status' => 'error',
					'errorDetail' => "รหัสประจำตัวนักเรียนซ้ำ"
				] );

			}else{	

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
				// $birthday = str_replace("-","",$studentForm->birthday);
				
				$birthday = $studentForm->YYYYbirthday.''.str_pad($studentForm->MMbirthday,2,"0",STR_PAD_LEFT).''.str_pad($studentForm->DDbirthday,2,"0",STR_PAD_LEFT);
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
				$student->SA_STUDENT_ID	= $studentForm->studentCardId;
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
				//
				$student->SA_CITIZEN_CODE = $studentForm->studentCitizenCode;
				$student->SA_SON_NO = $studentForm->sonNumber;
				$student->SA_OLDER_BROTHER = $studentForm->olderSon;
				$student->SA_YOUNGER_BROTHER = $studentForm->youngerSon;
				$student->SA_HOSPITAL_BORN = $studentForm->hospitalBorn;
				$student->SA_DISEASE = $studentForm->disease;
				$student->SA_FOOD_ALLERGY = $studentForm->foodALG;
				$student->SA_DRUG_ALLERGY = $studentForm->drugALG;
				$student->SA_CREDIT_NAME = $studentForm->creditName;
				$student->SA_CREDIT_LIMIT = $studentForm->creditLimit;
				$student->SA_FATHER_EMAIL = $studentForm->fatherEmail;
				$student->SA_FATHER_HOME_TEL = $studentForm->fatherHomeNumber;
				$student->SA_FATHER_CITIZEN_CODE = $studentForm->fatherCitizenCode;
				$student->SA_MOTHER_EMAIL = $studentForm->motherEmail;
				$student->SA_MOTHER_HOME_TEL = $studentForm->motherHomeNumber;
				$student->SA_MOTHER_CITIZEN_CODE = $studentForm->motherCitizenCode;
				$student->SA_RELIGION_REMARK = $studentForm->religionRemark;
				$student->SA_PARENT_STATUS_REMARK = $studentForm->parentStatusRemark;

				$student->SA_FATHER_JOB = $studentForm->fatherJob;
				$student->SA_FATHER_JOB_REMARK = $studentForm->fatherJobRemark;
				$student->SA_FATHER_JOB_SALARY = $studentForm->fatherJobSalary;
				$student->SA_MOTHER_JOB = $studentForm->motherJob;
				$student->SA_MOTHER_JOB_REMARK = $studentForm->motherJobRemark;
				$student->SA_MOTHER_JOB_SALARY = $studentForm->motherJobSalary;

				$student->SA_EMERGENCY_JOB = $studentForm->emergencyJob;
				$student->SA_EMERGENCY_JOB_REMARK = $studentForm->emergencyJobRemark;
				$student->SA_EMERGENCY_JOB_SALARY = $studentForm->emergencyJobSalary;
				$student->SA_EMERGENCY_EMAIL = $studentForm->emergencyEmail;
				$student->SA_EMERGENCY_HOME_TEL = $studentForm->emergencyHomeNumber;
				$student->SA_EMERGENCY_CITIZEN_CODE = $studentForm->emergencyCitizenCode;
				$student->SA_EMERGENCY_RELATION = $studentForm->emergencyRelation;

				$student->SA_READY_ROOM_ID = $studentForm->readyRoom;
				$student->SA_READY_YEAR = $studentForm->readyRoomYear;
				$student->SA_G1_ROOM_ID = $studentForm->G1Room;
				$student->SA_G1_YEAR = $studentForm->G1RoomYear;
				$student->SA_G2_ROOM_ID = $studentForm->G2Room;
				$student->SA_G2_YEAR = $studentForm->G2RoomYear;
				$student->SA_G3_ROOM_ID = $studentForm->G3Room;
				$student->SA_G3_YEAR = $studentForm->G3RoomYear;

				$student->SA_STUDENT_FOREIGNER_FLAG = 'N';
				if($studentForm->studentForeignerFlag){
					$student->SA_STUDENT_FOREIGNER_FLAG = 'Y';
					$student->SA_STUDENT_FOREIGNER = $studentForm->studentForeigner;
				}
				$student->SA_FATHER_FOREIGNER_FLAG = 'N';
				if($studentForm->fatherForeignerFlag){
					$student->SA_FATHER_FOREIGNER_FLAG = 'Y';
					$student->SA_FATHER_FOREIGNER = $studentForm->fatherForeigner;
				}
				$student->SA_MOTHER_FOREIGNER_FLAG = 'N';
				if($studentForm->motherForeignerFlag){
					$student->SA_MOTHER_FOREIGNER_FLAG = 'Y';
					$student->SA_MOTHER_FOREIGNER = $studentForm->motherForeigner;
				}
				$student->SA_EMERGENCY_FOREIGNER_FLAG = 'N';
				if($studentForm->emergencyForeignerFlag){
					$student->SA_EMERGENCY_FOREIGNER_FLAG = 'Y';
					$student->SA_EMERGENCY_FOREIGNER = $studentForm->emergencyForeigner;
				}

				
				
				
				


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

					$parent->SP_JOB = $studentForm->fatherJob;
					$parent->SP_JOB_REMARK = $studentForm->fatherJobRemark;
					$parent->SP_JOB_SALARY = $studentForm->fatherJobSalary;
					$parent->SP_EMAIL = $studentForm->fatherEmail;
					$parent->SP_HOME_TEL = $studentForm->fatherHomeNumber;
					$parent->SP_CITIZEN_CODE = $studentForm->fatherCitizenCode;

					$parent->SP_FOREIGNER_FLAG = 'N';
					if($studentForm->fatherForeignerFlag){
						$parent->SP_FOREIGNER_FLAG = 'Y';
						$parent->SP_FOREIGNER = $studentForm->fatherForeigner;
					}
					

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

					$parent->SP_JOB = $studentForm->motherJob;
					$parent->SP_JOB_REMARK = $studentForm->motherJobRemark;
					$parent->SP_JOB_SALARY = $studentForm->motherJobSalary;
					$parent->SP_EMAIL = $studentForm->motherEmail;
					$parent->SP_HOME_TEL = $studentForm->motherHomeNumber;
					$parent->SP_CITIZEN_CODE = $studentForm->motherCitizenCode;

					$parent->SP_FOREIGNER_FLAG = 'N';
					if($studentForm->motherForeignerFlag){
						$parent->SP_FOREIGNER_FLAG = 'Y';
					}
					$parent->SP_FOREIGNER = $studentForm->motherForeigner;

					$parent->save();

				}

				if($studentForm->emergencyParentFlag){
					$parent = new StudentParent();
					$parent->SA_ID = $student->SA_ID;
					$parent->SP_TITLE_NAME = $studentForm->emergencyPrefix;
					$parent->SP_FIRST_NAME = $studentForm->emergencyName;
					$parent->SP_LAST_NAME = $studentForm->emergencyLastName;
					$parent->SP_RELATION = $studentForm->emergencyRelation;
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

					$parent->SP_JOB = $studentForm->emergencyJob;
					$parent->SP_JOB_REMARK = $studentForm->emergencyJobRemark;
					$parent->SP_JOB_SALARY = $studentForm->emergencyJobSalary;
					$parent->SP_EMAIL = $studentForm->emergencyEmail;
					$parent->SP_HOME_TEL = $studentForm->emergencyHomeNumber;
					$parent->SP_CITIZEN_CODE = $studentForm->emergencyCitizenCode;
					$parent->SP_RELATION = $studentForm->emergencyRelation;

					$parent->SP_FOREIGNER_FLAG = 'N';
					if($studentForm->emergencyForeignerFlag){
						$parent->SP_FOREIGNER_FLAG = 'Y';
					}
					$parent->SP_FOREIGNER = $studentForm->emergencyForeigner;

					$parent->save();

				}


				DB::commit(); 
				
				return response ()->json ( [
						'status' => 'ok'
				] );
			}
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
		$where = ' AND a.USE_FLAG = "Y" ';
		try {
			if($request->studentCardId != ''  && !is_null($request->studentCardId)){
				$where = $where.' AND a.SA_STUDENT_ID LIKE "%'.$request->studentCardId.'%" ';	

			}
			if($request->studentFirstNameTH != ''&& !is_null($request->studentFirstNameTH)){
				$where = $where.' AND (a.SA_FIRST_NAME_TH LIKE "%'.$request->studentFirstNameTH.'%" OR a.SA_LAST_NAME_TH LIKE "%'.$request->studentFirstNameTH.'%" )';

			}
			if($request->studentNickNameTH != '' && !is_null($request->studentNickNameTH)){
				$where = $where.' AND a.SA_NICK_NAME_TH LIKE "%'.$request->studentNickNameTH.'%" ';	

			}
			if($request->searchRoom != '' && !is_null($request->searchRoom) && $request->searchRoom != "null"){
				if($request->searchG == 0 || $request->searchG == "0"){
					$where = $where.' AND (a.SA_READY_ROOM_ID  = '.$request->searchRoom.' 
											OR a.SA_G1_ROOM_ID  = '.$request->searchRoom.' 
											OR a.SA_G2_ROOM_ID  = '.$request->searchRoom.' 
											OR a.SA_G3_ROOM_ID  = '.$request->searchRoom.'
											)';
				}else if($request->searchG == 1 || $request->searchG == "1"){
					$where = $where.' AND a.SA_READY_ROOM_ID  = '.$request->searchRoom.' ';

				}else if($request->searchG == 2 || $request->searchG == "2"){
					$where = $where.' AND a.SA_G1_ROOM_ID  = '.$request->searchRoom.' ';

				}else if($request->searchG == 3 || $request->searchG == "3"){
					$where = $where.' AND a.SA_G2_ROOM_ID  = '.$request->searchRoom.' ';

				}else if($request->searchG == 4 || $request->searchG == "4"){
					$where = $where.' AND a.SA_G3_ROOM_ID  = '.$request->searchRoom.' ';

				}
			}
			if($request->searchYear != '' && !is_null($request->searchYear) && $request->searchYear != "null"){
				if($request->searchG == 0 || $request->searchG == "0"){
					$where = $where.' AND (a.SA_READY_YEAR = '.$request->searchYear.' 
											OR a.SA_G1_YEAR  = '.$request->searchYear.' 
											OR a.SA_G2_YEAR  = '.$request->searchYear.' 
											OR a.SA_G3_YEAR  = '.$request->searchYear.'
											)';
				}else if($request->searchG == 1 || $request->searchG == "1"){
					$where = $where.' AND a.SA_READY_YEAR  = '.$request->searchYear.' ';

				}else if($request->searchG == 2 || $request->searchG == "2"){
					$where = $where.' AND a.SA_G1_YEAR  = '.$request->searchYear.' ';

				}else if($request->searchG == 3 || $request->searchG == "3"){
					$where = $where.' AND a.SA_G2_YEAR  = '.$request->searchYear.' ';

				}else if($request->searchG == 4 || $request->searchG == "4"){
					$where = $where.' AND a.SA_G3_YEAR  = '.$request->searchYear.' ';

				}
			}

			if($request->subjectIdSearch != '' && !is_null($request->subjectIdSearch) && $request->subjectIdSearch != "null"){
				$where = $where.' AND s.SUBJECT_ID  = '.$request->subjectIdSearch.' ';
				$student = DB::select('SELECT * from STUDENT_ACCOUNT_VIEW a 
												,BILL b 
												, BILL_DETAIL bd 
												, SUBJECT s 
										WHERE b.BILL_STATUS = "P"
										AND a.SA_ID = b.SA_ID
										AND bd.BILL_ID = b.BILL_ID
										AND bd.SUBJECT_ID = s.SUBJECT_ID
										AND s.SUBJECT_TYPE = "S" '.$where .'
										ORDER BY a.SA_ID DESC');	

			}else{
				$student = DB::select('SELECT * from STUDENT_ACCOUNT_VIEW a WHERE 1=1 '.$where .'
									 ORDER BY SA_ID DESC');
			}

			// $student = StudentAccount::where('SA_FIRST_NAME_TH', 'LIKE', $studentName)->where('SA_STUDENT_ID', 'LIKE', $studentId)->where('USE_FLAG', 'Y')->get();
			// $student = DB::select('SELECT * from STUDENT_ACCOUNT_VIEW a '.$where .'
			// 						 ORDER BY SA_ID DESC');
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
			$studentCheck = StudentAccount::where('SA_ID','<>', $studentForm->studentId)->where('USE_FLAG','Y')->where('SA_STUDENT_ID',$studentForm->studentCardId)->first();
			if($studentCheck != null || $studentCheck != '' ||$studentCheck != []){
				return response ()->json ( [
					'status' => 'error',
					'errorDetail' => "รหัสประจำตัวนักเรียนซ้ำ"
				] );

			}else{	
				

			
			
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
				// $birthday = str_replace("-","",$studentForm->birthday);
				// $birthday = substr($birthday,0,4).''.substr($birthday,4,2).''.substr($birthday,6,2);
				$birthday = $studentForm->YYYYbirthday.''.str_pad($studentForm->MMbirthday,2,"0",STR_PAD_LEFT).''.str_pad($studentForm->DDbirthday,2,"0",STR_PAD_LEFT);
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
				$student->SA_STUDENT_ID	= $studentForm->studentCardId;
				if($studentForm->studentPic != '' && !is_null($studentForm->studentPic) && $studentForm->studentPic != "null"){
					$student->SA_PICTURE	= $studentForm->studentPic;
					$student->SA_PICTURE_TYPE	= (string)$studentForm->studentPicType;
				}
				if($studentForm->fatherPic != '' && !is_null($studentForm->fatherPic) && $studentForm->fatherPic != "null"){
					$student->SA_FATHER_PICTURE	= $studentForm->fatherPic;
					$student->SA_FATHER_PICTURE_TYPE	= (string)$studentForm->fatherPicType;
				}
				if($studentForm->motherPic != '' && !is_null($studentForm->motherPic) && $studentForm->motherPic != "null"){
					$student->SA_MOTHER_PICTURE	= $studentForm->motherPic;
					$student->SA_MOTHER_PICTURE_TYPE	= (string)$studentForm->motherPicType;
				}
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
				if($studentForm->emergencyPic != '' && !is_null($studentForm->emergencyPic) && $studentForm->emergencyPic != "null"){
					$student->SA_EMERGENCY_PICTURE	= $studentForm->emergencyPic;
					$student->SA_EMERGENCY_PICTURE_TYPE	= (string)$studentForm->emergencyPicType;
				}
				$student->SA_EMERGENCY_PARENT_FLAG = 'N';
				if($studentForm->emergencyParentFlag){
					$student->SA_EMERGENCY_PARENT_FLAG = 'Y';
				}


				$student->UPDATE_DATE = new \DateTime();	
				$student->UPDATE_BY = $userId;

				//
				$student->SA_CITIZEN_CODE = $studentForm->studentCitizenCode;
				$student->SA_SON_NO = $studentForm->sonNumber;
				$student->SA_OLDER_BROTHER = $studentForm->olderSon;
				$student->SA_YOUNGER_BROTHER = $studentForm->youngerSon;
				$student->SA_HOSPITAL_BORN = $studentForm->hospitalBorn;
				$student->SA_DISEASE = $studentForm->disease;
				$student->SA_FOOD_ALLERGY = $studentForm->foodALG;
				$student->SA_DRUG_ALLERGY = $studentForm->drugALG;
				$student->SA_CREDIT_NAME = $studentForm->creditName;
				$student->SA_CREDIT_LIMIT = $studentForm->creditLimit;
				$student->SA_FATHER_EMAIL = $studentForm->fatherEmail;
				$student->SA_FATHER_HOME_TEL = $studentForm->fatherHomeNumber;
				$student->SA_FATHER_CITIZEN_CODE = $studentForm->fatherCitizenCode;
				$student->SA_MOTHER_EMAIL = $studentForm->motherEmail;
				$student->SA_MOTHER_HOME_TEL = $studentForm->motherHomeNumber;
				$student->SA_MOTHER_CITIZEN_CODE = $studentForm->motherCitizenCode;

				$student->SA_RELIGION_REMARK = $studentForm->religionRemark;
				$student->SA_PARENT_STATUS_REMARK = $studentForm->parentStatusRemark;

				$student->SA_FATHER_JOB = $studentForm->fatherJob;
				$student->SA_FATHER_JOB_REMARK = $studentForm->fatherJobRemark;
				$student->SA_FATHER_JOB_SALARY = $studentForm->fatherJobSalary;
				$student->SA_MOTHER_JOB = $studentForm->motherJob;
				$student->SA_MOTHER_JOB_REMARK = $studentForm->motherJobRemark;
				$student->SA_MOTHER_JOB_SALARY = $studentForm->motherJobSalary;

				$student->SA_EMERGENCY_JOB = $studentForm->emergencyJob;
				$student->SA_EMERGENCY_JOB_REMARK = $studentForm->emergencyJobRemark;
				$student->SA_EMERGENCY_JOB_SALARY = $studentForm->emergencyJobSalary;
				$student->SA_EMERGENCY_EMAIL = $studentForm->emergencyEmail;
				$student->SA_EMERGENCY_HOME_TEL = $studentForm->emergencyHomeNumber;
				$student->SA_EMERGENCY_CITIZEN_CODE = $studentForm->emergencyCitizenCode;
				$student->SA_EMERGENCY_RELATION = $studentForm->emergencyRelation;

				$student->SA_READY_ROOM_ID = $studentForm->readyRoom;
				$student->SA_READY_YEAR = $studentForm->readyRoomYear;
				$student->SA_G1_ROOM_ID = $studentForm->G1Room;
				$student->SA_G1_YEAR = $studentForm->G1RoomYear;
				$student->SA_G2_ROOM_ID = $studentForm->G2Room;
				$student->SA_G2_YEAR = $studentForm->G2RoomYear;
				$student->SA_G3_ROOM_ID = $studentForm->G3Room;
				$student->SA_G3_YEAR = $studentForm->G3RoomYear;

				$student->SA_STUDENT_FOREIGNER_FLAG = 'N';
				if($studentForm->studentForeignerFlag){
					$student->SA_STUDENT_FOREIGNER_FLAG = 'Y';
					$student->SA_STUDENT_FOREIGNER = $studentForm->studentForeigner;
				}
				$student->SA_FATHER_FOREIGNER_FLAG = 'N';
				if($studentForm->fatherForeignerFlag){
					$student->SA_FATHER_FOREIGNER_FLAG = 'Y';
					$student->SA_FATHER_FOREIGNER = $studentForm->fatherForeigner;
				}
				$student->SA_MOTHER_FOREIGNER_FLAG = 'N';
				if($studentForm->motherForeignerFlag){
					$student->SA_MOTHER_FOREIGNER_FLAG = 'Y';
					$student->SA_MOTHER_FOREIGNER = $studentForm->motherForeigner;
				}
				$student->SA_EMERGENCY_FOREIGNER_FLAG = 'N';
				if($studentForm->emergencyForeignerFlag){
					$student->SA_EMERGENCY_FOREIGNER_FLAG = 'Y';
					$student->SA_EMERGENCY_FOREIGNER = $studentForm->emergencyForeigner;
				}
				
				
				
				


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
							// $parentFather->SP_PICTURE = $studentForm->fatherPic;
							// $parentFather->SP_PICTURE_TYPE = (string)$studentForm->fatherPicType;
							if($studentForm->fatherPic != '' && !is_null($studentForm->fatherPic) && $studentForm->fatherPic != "null"){
								$parentFather->SP_PICTURE = $studentForm->fatherPic;
								$parentFather->SP_PICTURE_TYPE = (string)$studentForm->fatherPicType;
							}
							$parentFather->SP_RELATION_TYPE = 'D';
							$parentFather->UPDATE_DATE = new \DateTime();
							$parentFather->UPDATE_BY = $userId;

							$parentFather->SP_JOB = $studentForm->fatherJob;
							$parentFather->SP_JOB_REMARK = $studentForm->fatherJobRemark;
							$parentFather->SP_JOB_SALARY = $studentForm->fatherJobSalary;
							$parentFather->SP_EMAIL = $studentForm->fatherEmail;
							$parentFather->SP_HOME_TEL = $studentForm->fatherHomeNumber;
							$parentFather->SP_CITIZEN_CODE = $studentForm->fatherCitizenCode;

							$parentFather->SP_FOREIGNER_FLAG = 'N';
							if($studentForm->fatherForeignerFlag){
								$parentFather->SP_FOREIGNER_FLAG = 'Y';
								$parentFather->SP_FOREIGNER = $studentForm->fatherForeigner;
							}
							

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
						// $parent->SP_PICTURE = $studentForm->fatherPic;
						// $parent->SP_PICTURE_TYPE = (string)$studentForm->fatherPicType;
						if($studentForm->fatherPic != '' && !is_null($studentForm->fatherPic) && $studentForm->fatherPic != "null"){
							$parentFather->SP_PICTURE = $studentForm->fatherPic;
							$parentFather->SP_PICTURE_TYPE = (string)$studentForm->fatherPicType;
						}
						$parent->SP_RELATION_TYPE = 'D';
						$parent->SA_ID	= $studentForm->studentId;
						$parent->CREATE_DATE = new \DateTime();
						$parent->CREATE_BY = $userId;
						$parent->UPDATE_DATE = new \DateTime();
						$parent->UPDATE_BY = $userId;

						$parent->SP_JOB = $studentForm->fatherJob;
						$parent->SP_JOB_REMARK = $studentForm->fatherJobRemark;
						$parent->SP_JOB_SALARY = $studentForm->fatherJobSalary;
						$parent->SP_EMAIL = $studentForm->fatherEmail;
						$parent->SP_HOME_TEL = $studentForm->fatherHomeNumber;
						$parent->SP_CITIZEN_CODE = $studentForm->fatherCitizenCode;

						$parent->SP_FOREIGNER_FLAG = 'N';
						if($studentForm->fatherForeignerFlag){
							$parent->SP_FOREIGNER_FLAG = 'Y';
							$parent->SP_FOREIGNER = $studentForm->fatherForeigner;
						}
						

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
							$parentMother->SP_TITLE_NAME = $studentForm->motherPrefix;
							$parentMother->SP_FIRST_NAME = $studentForm->motherName;
							$parentMother->SP_LAST_NAME = $studentForm->motherLastName;
							//$parentMother->SP_RELATION = 'มารดา';
							$parentMother->SP_ADDRESS = $studentForm->motherAddress;
							$parentMother->SP_PROVINCE = $studentForm->motherProvince;
							$parentMother->SP_AMPHUR = $studentForm->motherAmphur;
							$parentMother->SP_DISTRICT = $studentForm->motherDistrict;
							$parentMother->SP_TEL = $studentForm->motherTel;
							// $parentMother->SP_PICTURE = $studentForm->motherPic;
							// $parentMother->SP_PICTURE_TYPE = (string)$studentForm->motherPicType;
							if($studentForm->motherPic != '' && !is_null($studentForm->motherPic) && $studentForm->motherPic != "null"){
								$parentMother->SP_PICTURE = $studentForm->motherPic;
								$parentMother->SP_PICTURE_TYPE = (string)$studentForm->motherPicType;
							}
							$parentMother->SP_RELATION_TYPE = 'M';
							$parentMother->UPDATE_DATE = new \DateTime();
							$parentMother->UPDATE_BY = $userId;

							$parentMother->SP_JOB = $studentForm->motherJob;
							$parentMother->SP_JOB_REMARK = $studentForm->motherJobRemark;
							$parentMother->SP_JOB_SALARY = $studentForm->motherJobSalary;
							$parentMother->SP_EMAIL = $studentForm->motherEmail;
							$parentMother->SP_HOME_TEL = $studentForm->motherHomeNumber;
							$parentMother->SP_CITIZEN_CODE = $studentForm->motherCitizenCode;

							$parentMother->SP_FOREIGNER_FLAG = 'N';
							if($studentForm->motherForeignerFlag){
								$parentMother->SP_FOREIGNER_FLAG = 'Y';
							}
							$parentMother->SP_FOREIGNER = $studentForm->motherForeigner;
							
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
						// $parent->SP_PICTURE = $studentForm->motherPic;
						// $parent->SP_PICTURE_TYPE = (string)$studentForm->motherPicType;
						if($studentForm->motherPic != '' && !is_null($studentForm->motherPic) && $studentForm->motherPic != "null"){
							$parentMother->SP_PICTURE = $studentForm->motherPic;
							$parentMother->SP_PICTURE_TYPE = (string)$studentForm->motherPicType;
						}
						$parent->SP_RELATION_TYPE = 'M';
						$parent->SA_ID	= $studentForm->studentId;
						$parent->CREATE_DATE = new \DateTime();
						$parent->CREATE_BY = $userId;
						$parent->UPDATE_DATE = new \DateTime();
						$parent->UPDATE_BY = $userId;

						$parent->SP_JOB = $studentForm->motherJob;
						$parent->SP_JOB_REMARK = $studentForm->motherJobRemark;
						$parent->SP_JOB_SALARY = $studentForm->motherJobSalary;
						$parent->SP_EMAIL = $studentForm->motherEmail;
						$parent->SP_HOME_TEL = $studentForm->motherHomeNumber;
						$parent->SP_CITIZEN_CODE = $studentForm->motherCitizenCode;

						$parent->SP_FOREIGNER_FLAG = 'N';
						if($studentForm->motherForeignerFlag){
							$parent->SP_FOREIGNER_FLAG = 'Y';
						}
						$parent->SP_FOREIGNER = $studentForm->motherForeigner;

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
							$parentEmergency->SP_RELATION = $studentForm->emergencyRelation;
							$parentEmergency->SP_ADDRESS = $studentForm->emergencyAddress;
							$parentEmergency->SP_PROVINCE = $studentForm->emergencyProvince;
							$parentEmergency->SP_AMPHUR = $studentForm->emergencyAmphur;
							$parentEmergency->SP_DISTRICT = $studentForm->emergencyDistrict;
							$parentEmergency->SP_TEL = $studentForm->emergencyTel;
							// $parentEmergency->SP_PICTURE = $studentForm->emergencyPic;
							// $parentEmergency->SP_PICTURE_TYPE = (string)$studentForm->emergencyPicType;
							if($studentForm->emergencyPic != '' && !is_null($studentForm->emergencyPic) && $studentForm->emergencyPic != "null"){
								$parentEmergency->SP_PICTURE = $studentForm->emergencyPic;
								$parentEmergency->SP_PICTURE_TYPE = (string)$studentForm->emergencyPicType;
							}
							$parentEmergency->SP_RELATION_TYPE = 'E';
							$parentEmergency->UPDATE_DATE = new \DateTime();
							$parentEmergency->UPDATE_BY = $userId;

							$parentEmergency->SP_JOB = $studentForm->emergencyJob;
							$parentEmergency->SP_JOB_REMARK = $studentForm->emergencyJobRemark;
							$parentEmergency->SP_JOB_SALARY = $studentForm->emergencyJobSalary;
							$parentEmergency->SP_EMAIL = $studentForm->emergencyEmail;
							$parentEmergency->SP_HOME_TEL = $studentForm->emergencyHomeNumber;
							$parentEmergency->SP_CITIZEN_CODE = $studentForm->emergencyCitizenCode;

							$parentEmergency->SP_FOREIGNER_FLAG = 'N';
							if($studentForm->emergencyForeignerFlag){
								$parentEmergency->SP_FOREIGNER_FLAG = 'Y';
							}

							$parentEmergency->SP_FOREIGNER = $studentForm->emergencyForeigner;

							$parentEmergency->save();
						}
					}else{
						$parent = new StudentParent();
						$parent->SP_TITLE_NAME = $studentForm->emergencyPrefix;
						$parent->SP_FIRST_NAME = $studentForm->emergencyName;
						$parent->SP_LAST_NAME = $studentForm->emergencyLastName;
						$parent->SP_RELATION = $studentForm->emergencyRelation;
						$parent->SP_ADDRESS = $studentForm->emergencyAddress;
						$parent->SP_PROVINCE = $studentForm->emergencyProvince;
						$parent->SP_AMPHUR = $studentForm->emergencyAmphur;
						$parent->SP_DISTRICT = $studentForm->emergencyDistrict;
						$parent->SP_TEL = $studentForm->emergencyTel;
						// $parent->SP_PICTURE = $studentForm->emergencyPic;
						// $parent->SP_PICTURE_TYPE = (string)$studentForm->emergencyPicType;
						if($studentForm->emergencyPic != '' && !is_null($studentForm->emergencyPic) && $studentForm->emergencyPic != "null"){
							$parentEmergency->SP_PICTURE = $studentForm->emergencyPic;
							$parentEmergency->SP_PICTURE_TYPE = (string)$studentForm->emergencyPicType;
						}
						$parent->SP_RELATION_TYPE = 'E';
						$parent->SA_ID	= $studentForm->studentId;
						$parent->CREATE_DATE = new \DateTime();
						$parent->CREATE_BY = $userId;
						$parent->UPDATE_DATE = new \DateTime();
						$parent->UPDATE_BY = $userId;

						$parent->SP_JOB = $studentForm->emergencyJob;
						$parent->SP_JOB_REMARK = $studentForm->emergencyJobRemark;
						$parent->SP_JOB_SALARY = $studentForm->emergencyJobSalary;
						$parent->SP_EMAIL = $studentForm->emergencyEmail;
						$parent->SP_HOME_TEL = $studentForm->emergencyHomeNumber;
						$parent->SP_CITIZEN_CODE = $studentForm->emergencyCitizenCode;

						$parent->SP_FOREIGNER_FLAG = 'N';
						if($studentForm->emergencyForeignerFlag){
							$parent->SP_FOREIGNER_FLAG = 'Y';
						}
						$parent->SP_FOREIGNER = $studentForm->emergencyForeigner;

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
			
			
			}
			
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

	public function isStudentCardIdDulpicate($studentId,$studentCardId) {
		$student;
		try {
			if($studentId == 'ADD'){
				$student = StudentAccount::where('USE_FLAG','Y')->where('SA_STUDENT_ID',$studentCardId)->first();
			}else{
				$student = StudentAccount::where('SA_ID','<>', $studentForm->studentId)->where('USE_FLAG','Y')->where('SA_STUDENT_ID',$studentCardId)->first();
			}

			if($student != null || $student != '' ||$student != []){
				return false;
			}else{
				return true;
			}
			
			
		} catch ( \Exception $e ) {
			DB::rollBack ();
			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $request->userLoginId
			] );
		}
	}
}
