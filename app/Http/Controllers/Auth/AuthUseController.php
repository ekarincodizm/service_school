<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\User;

class AuthUseController extends Controller {

	public static function login($userName, $password) {
		$authUser = new AuthUseController ();
		
		$users = $authUser->findUser ( $userName, $password );
		
		if (isset ( $users )) {
			
			session ( [ 
					'usserSeccion' => $users 
			] );
			
		} else {
		}
	}
	public static function check() {
		try {
			if (session ()->has ( 'usserSeccion' )) {
				return true;
			} else {
				return false;
			}
		} catch ( \Illuminate\Session\TokenMismatchException $te ) {
			return false; // do nothing
		}
	}
	public static function logout() {
		try {
			session ()->flush ();
		} catch ( \Illuminate\Session\TokenMismatchException $te ) {
			return; // do nothing
		}
	}
	public static function usserSection() {
		try {
			if (session ()->has ( 'usserSeccion' )) {
				return session ()->get ( 'usserSeccion' )[0];
			} else {
				return null;
			}
		} catch ( \Illuminate\Session\TokenMismatchException $te ) {
			return null;
		}
	}

	private function findUser($userName, $password) {
		$users = User::where ( 'USER_LOGIN', '=', '' . $userName . '' )->where( 'USE_FLAG', '=', 'Y' )->get ();
		
		foreach ( $users as $user ) {
			if (password_verify ( $password, $user->USER_PASSWORD )) {
				return $users;
			}
			
			return null;
		}
	}
}
