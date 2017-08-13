<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Model\Province;
use App\Model\Amphur;
use App\Model\District;

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


	public function postAmphurList(Request $request) {
		$amphur = Amphur::where('PROVINCE_ID',$request->province)->get();
		return response ()->json ( [
						'status' => 'ok',
						'amphur' => $amphur,
				] );
	}

	public function postDistrictList(Request $request) {
		$district = District::where('AMPHUR_ID',$request->amphur)->get();
		return response ()->json ( [
						'status' => 'ok',
						'district' => $district,
				] );
	}

	public function postPostCode(Request $request) {
		$amphur = Amphur::where('AMPHUR_ID',$request->amphur)->get();
		return response ()->json ( [
						'status' => 'ok',
						'amphur' => $amphur,
				] );
	}
    
}
