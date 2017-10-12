<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\StudentParent;
use App\Model\StudentAccount;
use App\Model\Province;
use App\Model\Amphur;
use App\Model\District;
use DB;

class ParentController extends Controller
{

    public function postSearchParent(Request $request) {
		try {
			$studentId = $request->studentId;
			$parents = StudentParent::where('SA_ID',$studentId)->where('USE_FLAG' , 'Y')->get();
			$addressList = array();
			$postCodeList = array();
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

    public function postStoreParent(Request $request) {
        $parent;
		try {
			$postdata = file_get_contents("php://input");
			// $studentForm = json_decode($postdata)->studentModel;
            $parentForm = json_decode($postdata);
            DB::beginTransaction();
				$parent = new StudentParent();
				$parent->SA_ID = $parentForm->studentId;
				$parent->SP_TITLE_NAME = $parentForm->parentPrefix;
				$parent->SP_FIRST_NAME = $parentForm->parentFirstName;
				$parent->SP_LAST_NAME = $parentForm->parentLastName;
				$parent->SP_RELATION = $parentForm->relationship;
				$parent->SP_ADDRESS = $parentForm->parentAddress;
				$parent->SP_PROVINCE = $parentForm->parentProvince;
				$parent->SP_AMPHUR = $parentForm->parentAmphur;
				$parent->SP_DISTRICT = $parentForm->parentDistrict;
				$parent->SP_TEL = $parentForm->parentTel;
				$parent->SP_PICTURE = $parentForm->parentPic;
				$parent->SP_PICTURE_TYPE = (string)$parentForm->parentPicType;
				$parent->CREATE_DATE = new \DateTime();
				$parent->CREATE_BY = $parentForm->userId;
				$parent->UPDATE_DATE = new \DateTime();
				$parent->UPDATE_BY = $parentForm->userId;
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

    public function postUpdateParent(Request $request) {
		try {
			
			
			$postdata = file_get_contents("php://input");
            $parentForm = json_decode($postdata);
			
			DB::beginTransaction();
            $parent = StudentParent::find($parentForm->parentId);   
			$parent->SP_TITLE_NAME = $parentForm->parentPrefix;
			$parent->SP_FIRST_NAME = $parentForm->parentFirstName;
			$parent->SP_LAST_NAME = $parentForm->parentLastName;
			$parent->SP_RELATION = $parentForm->relationship;
			$parent->SP_ADDRESS = $parentForm->parentAddress;
			$parent->SP_PROVINCE = $parentForm->parentProvince;
			$parent->SP_AMPHUR = $parentForm->parentAmphur;
			$parent->SP_DISTRICT = $parentForm->parentDistrict;	
            $parent->SP_TEL = $parentForm->parentTel;
			$parent->SP_PICTURE = $parentForm->parentPic;
			$parent->SP_PICTURE_TYPE = (string)$parentForm->parentPicType;
			$parent->UPDATE_DATE = new \DateTime();
			$parent->UPDATE_BY = $parentForm->userId;
			$parent->save();

			if($parent->SP_RELATION_TYPE == 'D'){
				$student = StudentAccount::find($parent->SA_ID);
				$student->SA_FATHER_TITLE_NAME_TH = $parentForm->parentPrefix;
				$student->SA_FATHER_NAME = $parentForm->parentFirstName;
				$student->SA_FATHER_LAST_NAME = $parentForm->parentLastName;
				$student->SA_FATHER_ADDRESS = $parentForm->parentAddress;	
				$student->SA_FATHER_PROVINCE = $parentForm->parentProvince;
				$student->SA_FATHER_AMPHUR = $parentForm->parentAmphur;
				$student->SA_FATHER_DISTRICT = $parentForm->parentDistrict;	
				$student->SA_FATHER_TEL = $parentForm->parentTel;
				$student->SA_FATHER_PICTURE	= $parentForm->parentPic;
				$student->SA_FATHER_PICTURE_TYPE	= (string)$parentForm->parentPicType;
				$student->save();
			}
			if($parent->SP_RELATION_TYPE == 'M'){
				$student = StudentAccount::find($parent->SA_ID);
				$student->SA_MOTHER_TITLE_NAME_TH = $parentForm->parentPrefix;
				$student->SA_MOTHER_NAME = $parentForm->parentFirstName;
				$student->SA_MOTHER_LAST_NAME = $parentForm->parentLastName;
				$student->SA_MOTHER_ADDRESS = $parentForm->parentAddress;	
				$student->SA_MOTHER_PROVINCE = $parentForm->parentProvince;
				$student->SA_MOTHER_AMPHUR = $parentForm->parentAmphur;
				$student->SA_MOTHER_DISTRICT = $parentForm->parentDistrict;	
				$student->SA_MOTHER_TEL = $parentForm->parentTel;
				$student->SA_MOTHER_PICTURE	= $parentForm->parentPic;
				$student->SA_MOTHER_PICTURE_TYPE	= (string)$parentForm->parentPicType;
				$student->save();
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

    public function postRemoveParent(Request $request) {
		$studentId;
		try {
			
			DB::beginTransaction();
			$parent = StudentParent::find($request->parentId);
			$parent->UPDATE_DATE = new \DateTime();
			$parent->UPDATE_BY = $request->userLoginId;
			$parent->USE_FLAG = 'N';
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
}
