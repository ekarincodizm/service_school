<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\Province;

class AddressController extends Controller
{
    public function getIndex() {
		return response ()->json ( [
						'status' => 'ok',
				] );
	}


    public function getProvince(){
        $province = Province::all();
		return response ()->json ( [
						'status' => 'ok',
                        'province' => $province,
				] );
	}


	public function doAmphur(Request $request){
        error_log($request);
		return response ()->json ( [
						'status' => 'ok',
				] );
	}
    
}
