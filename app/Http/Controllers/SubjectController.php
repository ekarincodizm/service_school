<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Model\Subject;

class SubjectController extends Controller {
	public function getIndex() {
	}
	public function postStoreSubject(Request $request) {
		$subjectName;
		$subjectCode;
		try {
			$subjectName = $request->subjectName;
			$subjectCode = $request->subjectCode;
			
			DB::beginTransaction();
			$subject = new Subject();
			$subject->SUBJECT_NAME = $subjectName;
			$subject->SUBJECT_CODE = $subjectCode;
			$subject->CREATE_DATE = new \DateTime();
			$subject->CREATE_BY = $request->userLoginId;
			$subject->UPDATE_DATE = new \DateTime();
			$subject->UPDATE_BY = $request->userLoginId;
			$subject->save();
			
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
	
	public function postSearchSubject(Request $request) {
		$subjectName;
		$subjectCode;
		try {
			
			$subjectName = '%'.$request->subjectName.'%';
			$subjectCode = '%'.$request->subjectCode.'%';
			
			$subject = Subject::where('SUBJECT_NAME', 'LIKE', $subjectName)
				->where('SUBJECT_CODE', 'LIKE', $subjectCode)
				->where('USE_FLAG', 'Y')
				->where('SUBJECT_TYPE', 'S')
				->orderBy('SUBJECT_CODE', 'asc')->get();
			
			return response()->json($subject);

			
		} catch ( \Exception $e ) {
			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function postUpdateSubject(Request $request) {
		$subjectName;
		$subjectId;
		$subjectCode;
		try {
			$subjectId = $request->subjectId;
			$subjectName = $request->subjectName;
			$subjectCode = $request->subjectCode;
			
			DB::beginTransaction();
			$subject = Subject::find($subjectId);
			$subject->SUBJECT_NAME = $subjectName;
			$subject->SUBJECT_CODE = $subjectCode;
			$subject->UPDATE_DATE = new \DateTime();
 			$subject->UPDATE_BY = $request->userLoginId;
			$subject->save();
			
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
	
	public function postRemoveSubject(Request $request) {
		$subjectId;
		try {
			$subjectId = $request->subjectId;
			
			DB::beginTransaction();
			$subject = Subject::find($subjectId);
			$subject->UPDATE_DATE = new \DateTime();
			$subject->UPDATE_BY = $request->userLoginId;
			$subject->USE_FLAG = 'N';
			$subject->save();
			
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


	

	public function postSearchOthers(Request $request) {
		$othersName;
		try {
			
			$othersName = '%'.$request->subjectName.'%';
			
			$others = Subject::where('SUBJECT_NAME', 'LIKE', $othersName)
				->where('USE_FLAG', 'Y')
				->where('SUBJECT_TYPE', 'O')->get();
			
			return response()->json($others);

			
		} catch ( \Exception $e ) {
			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}


	public function postStoreOthers(Request $request) {
		$othersName;
		try {
			$othersName = $request->subjectName;
			
			DB::beginTransaction();
			$subject = new Subject();
			$subject->SUBJECT_NAME = $othersName;
			$subject->SUBJECT_TYPE = 'O';
			$subject->CREATE_DATE = new \DateTime();
			$subject->CREATE_BY = $request->userLoginId;
			$subject->UPDATE_DATE = new \DateTime();
			$subject->UPDATE_BY = $request->userLoginId;
			$subject->save();
			
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

	public function postUpdateOthers(Request $request) {
		$othersName;
		$othersId;
		try {
			$othersId = $request->subjectId;
			$othersName = $request->subjectName;
			
			DB::beginTransaction();
			$subject = Subject::find($othersId);
			$subject->SUBJECT_NAME = $othersName;
			$subject->UPDATE_DATE = new \DateTime();
 			$subject->UPDATE_BY = $request->userLoginId;
			$subject->save();
			
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

	public function postSearchAll(Request $request) {

		try {
			
			$subject = Subject::where('USE_FLAG', 'Y')
				->where('SUBJECT_TYPE', 'S')
				->orderBy('SUBJECT_CODE', 'asc')->get();

			$others = Subject::where('USE_FLAG', 'Y')
				->where('SUBJECT_TYPE', 'O')
				->orderBy('SUBJECT_NAME', 'asc')->get();

			$subjectAll = array();

			foreach ($subject as $key => $value) {
				$subjectAll[$key] = array(
										'SUBJECT_ID' => $value->SUBJECT_ID,
										'SUBJECT_CODE' => $value->SUBJECT_CODE,
										'SUBJECT_NAME' => $value->SUBJECT_NAME,
										'SUBJECT_TYPE' => $value->SUBJECT_TYPE
										);
		   }

		   foreach ($others as $key => $value) {
			$subjectAll[$key+count($subject)] = array(
									'SUBJECT_ID' => $value->SUBJECT_ID,
									'SUBJECT_CODE' => $value->SUBJECT_CODE,
									'SUBJECT_NAME' => $value->SUBJECT_NAME,
									'SUBJECT_TYPE' => $value->SUBJECT_TYPE
									);
	   }
			
			return response()->json($subjectAll);

			
		} catch ( \Exception $e ) {
			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}

	public function postFindById(Request $request) {
		
				try {
					
					$subject = Subject::find($request->id);
		
					return response()->json($subject);
					
				} catch ( \Exception $e ) {
					return response ()->json ( [
							'status' => 'error',
							'errorDetail' => $e->getMessage()
					] );
				}
			}
	
}
