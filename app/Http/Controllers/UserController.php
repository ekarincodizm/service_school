<?php

namespace App\Http\Controllers;
use App\Model\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
	public function getIndex() {
		$user = User::all();
		return response()->json(['newResultSum' => 1, 'newResultSumId'=> 2]);
// 		return "FUCK YOU";
	}
	
	public function getTest2() {
		$user = User::all();
		return response()->json(['newResultSum' => 1, 'newResultSumId'=> 2]);
		// 		return "FUCK YOU";
	}
	
	public function postTest4(Request $request) {
		$user = User::all();
		return response()->json(['newResultSum' => 1, 'newResultSumId'=> $request->var1]);
		// 		return "FUCK YOU";
	}
	
	public function getTest($test){
		
		$asd = json_decode($test, true);
		
		return response()->json(['newResultSum' => $asd['var1'], 'newResultSumId'=> 2]);
	}
}
