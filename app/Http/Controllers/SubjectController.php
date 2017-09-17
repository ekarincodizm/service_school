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
	
}
