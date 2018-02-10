<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Province;
use App\Model\Amphur;
use App\Model\District;

class StudentParent extends Model
{
	protected $table = 'STUDENT_PARENT'; // Real Name Table
	protected $primaryKey = 'SP_ID';
	public $timestamps = false;


	public function getFullAddressAttribute() {
		$address = ''.$this->attributes['SP_ADDRESS'];

		if($this->attributes['SP_DISTRICT'] != null && $this->attributes['SP_DISTRICT'] != ''){
			$address = $address.' ต.'.District::find($this->attributes['SP_DISTRICT'])->DISTRICT_NAME;
		}

		if($this->attributes['SP_AMPHUR'] != null && $this->attributes['SP_AMPHUR'] != ''){
			$address = $address.' อ.'.Amphur::find($this->attributes['SP_AMPHUR'])->AMPHUR_NAME;
		}

		if($this->attributes['SP_PROVINCE'] != null && $this->attributes['SP_PROVINCE'] != ''){
			$address = $address.' จ.'.Province::find($this->attributes['SP_PROVINCE'])->PROVINCE_NAME;
		}
		
		
		if($this->attributes['SP_AMPHUR'] != null && $this->attributes['SP_AMPHUR'] != ''){
			$address = $address.' '.Amphur::find($this->attributes['SP_AMPHUR'])->POSTCODE;
		}


		return $address;
	}

	public function getDistrictAttribute() {
		return District::find($this->attributes['SP_DISTRICT']);
	}

	public function getAmphurAttribute() {
		return Amphur::find($this->attributes['SP_AMPHUR']);
	}

	public function getProvinceAttribute() {
		return Province::find($this->attributes['SP_PROVINCE']);
	}
	
}
