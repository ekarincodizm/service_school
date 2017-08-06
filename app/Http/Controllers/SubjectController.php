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
		try {
			$subjectName = $request->subjectName;
			
			DB::beginTransaction();
			$subject = new Subject();
			$subject->SUBJECT_NAME = $subjectName;
			$subject->CREATE_DATE = new \DateTime();
			$subject->CREATE_BY = '-';
			$subject->UPDATE_DATE = new \DateTime();
			$subject->UPDATE_BY = '-';
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
		try {
			
			$subjectName = '%'.$request->subjectName.'%';
			
			$subject = Subject::where('SUBJECT_NAME', 'LIKE', $subjectName)->where('USE_FLAG', 'Y')->get();
			
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
		try {
			$subjectId = $request->subjectId;
			$subjectName = $request->subjectName;
			
			DB::beginTransaction();
			$subject = Subject::find($subjectId);
			$subject->SUBJECT_NAME = $subjectName;
			$subject->UPDATE_DATE = new \DateTime();
 			$subject->UPDATE_BY = '-';
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
			$subject->UPDATE_BY = '-';
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
