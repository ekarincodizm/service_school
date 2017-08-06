<?php

namespace App\Http\Controllers;
use App\Model\User;
use Illuminate\Http\Request;
use DB;
use App\Http\Controllers\Auth\AuthUseController;

class UserController extends Controller
{
	public function getIndex() {
	}
	
	public function postLogin(Request $request) {
		try{
			$username = $request->input ( 'userLogin' );
			$password = $request->input ( 'userPassword' );

			AuthUseController::login ( $username, $password );
			
			if (AuthUseController::check ()) {
				return response ()->json ( [
						'status' => 'ok',
						'userSession' => AuthUseController::usserSection()
				] );
			} else {
				return response ()->json ( [
						'status' => 'error',
						'errorDetail' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง'
				] );
			}
		}catch ( \Exception $e ) {

			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}
	
	public function postSearchUser(Request $request) {
		$userTitle = null;
		$userFirstName = null;
		$userLastName = null;
		$user = null;
		try {

			$userTitle = $request->userTitleName;
			$userFirstName = $request->userFirstName;
			$userLastName = $request->userLastName;

			$user = User::where('USE_FLAG', 'Y');

			if(!is_null($userTitle) && $userTitle != ''){
				$user = $user->where('USER_TITLE_NAME', 'LIKE', '%'.$userTitle.'%');
			}

			if(!is_null($userFirstName) && $userFirstName != ''){
				$user = $user->where('USER_FIRST_NAME', 'LIKE', '%'.$userFirstName.'%');
			}

			if(!is_null($userLastName) && $userLastName != ''){
				$user = $user->where('USER_Last_NAME', 'LIKE', '%'.$userLastName.'%');
			}
			
			$user = $user->get();

			return response()->json($user);
			
		} catch ( \Exception $e ) {

			return response ()->json ( [
					'status' => 'error',
					'errorDetail' => $e->getMessage()
			] );
		}
	}
	
	public function postStoreUser(Request $request) {
		$userCount;
		$user;
		try {
			$userCount = User::where('USE_FLAG','Y')->where('USER_LOGIN', $request->userLogin)->count();

			if($userCount > 0){
				return response ()->json ( [
					'status' => 'warning',
					'warningMessage' => 'ชื่อผู้ใช้ซ้ำ'
				] );
			}
			
			DB::beginTransaction();
			$user = new User();
			$user->USER_TITLE_NAME = $request->userTitleName;
			$user->USER_FIRST_NAME = $request->userFirstName;
			$user->USER_LAST_NAME = $request->userLastName;
			$user->USER_PHONE = $request->userPhone;
			$user->USER_EMAIL = $request->userEmail;
			$user->USER_LOGIN = $request->userLogin;
			$user->USER_PASSWORD = password_hash ( $request->userPassword, PASSWORD_BCRYPT );
			$user->CREATE_DATE = new \DateTime();
			$user->CREATE_BY = $request->userLoginId;
			$user->UPDATE_DATE = new \DateTime();
			$user->UPDATE_BY = $request->userLoginId;
			$user->save();
			
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

	public function postUpdateUser(Request $request) {
		$userCount;
		$user;
		try {
			
			$userCount = User::where('USE_FLAG','Y')->where('USER_ID','<>',$request->userId)->where('USER_LOGIN', $request->userLogin)->count();

			if($userCount > 0){
				return response ()->json ( [
					'status' => 'warning',
					'warningMessage' => 'ชื่อผู้ใช้ซ้ำ'
				] );
			}
			
			DB::beginTransaction();
			$user = User::find($request->userId);
			$user->USER_TITLE_NAME = $request->userTitleName;
			$user->USER_FIRST_NAME = $request->userFirstName;
			$user->USER_LAST_NAME = $request->userLastName;
			$user->USER_PHONE = $request->userPhone;
			$user->USER_EMAIL = $request->userEmail;
			$user->USER_LOGIN = $request->userLogin;

			if($request->userUpdatePassword){
				$user->USER_PASSWORD = password_hash ( $request->userPassword, PASSWORD_BCRYPT );
			}

			//$user->CREATE_DATE = new \DateTime();
			//$user->CREATE_BY = '-';
			$user->UPDATE_DATE = new \DateTime();
			$user->UPDATE_BY = $request->userLoginId;
			$user->save();
			
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

	public function postRemoveUser(Request $request) {
		$user;
		try {
			DB::beginTransaction();
			$user = User::find($request->userId);
			$user->USE_FLAG = 'N';
			//$user->USER_PASSWORD = password_hash ( $request->userPassword, PASSWORD_BCRYPT );
			//$user->CREATE_DATE = new \DateTime();
			//$user->CREATE_BY = '-';
			$user->UPDATE_DATE = new \DateTime();
			$user->UPDATE_BY = $request->userLoginId;;
			$user->save();
			
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
