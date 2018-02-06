<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\UtilController\DateUtil;
use App\Model\FutureSchool;
use App\Model\StudentParent;

class StudentAccount extends Model
{
	protected $table = 'STUDENT_ACCOUNT'; // Real Name Table
	protected $primaryKey = 'SA_ID';
	public $timestamps = false;

	public function getFutureSchoolNameAttribute() {
		if($this->attributes['SA_FUTURE_SCHOOL'] != null && $this->attributes['SA_FUTURE_SCHOOL'] != ''){
			return FutureSchool::find($this->attributes['SA_FUTURE_SCHOOL'])->FUTURE_SCHOOL_NAME;
		}else{
			return "";
		}
	}

	public function getParentAttribute() {
		return StudentParent::where('SA_ID',$this->attributes['SA_ID'])->where('USE_FLAG' , 'Y')->get();
		
	}

	public function getBirthDayAttribute()
	{
		return substr($this->attributes['SA_BIRTH_DATE'],6);
	}

	public function getBirthMonthAttribute()
	{
		return substr($this->attributes['SA_BIRTH_DATE'],4, 2);
	}

	public function getBirthYearAttribute()
	{
		return substr($this->attributes['SA_BIRTH_DATE'],0, 4);
	}

	public function getAgeAttribute()
	{

		// 1
		$differentYearCal = DateUtil::getCurrentThaiYear() - $this->getBirthYearAttribute();

		//1/12
		$differentMonth = DateUtil::getCurrentMonth2Digit() - $this->getBirthMonthAttribute();

		//((1/30)/12)
		$differentDay = DateUtil::getCurrentDay() - $this->getBirthDayAttribute();


		if($differentMonth < 0){
			$differentYearCal = $differentYearCal - 1;
			$differentMonthCal = 12 +  $differentMonth;
		}else{
			$differentMonthCal = $differentMonth;
		}

		if($differentDay < 0){
			$differentMonthCal = $differentMonthCal - 1;
			$differentDayCal = 30 +  $differentDay;
		}else{
			$differentDayCal = $differentDay;
		}

		return number_format(($differentYearCal) + ($differentMonthCal/12) + (($differentDayCal/30)/12), 2, '.', ' ');;
	}


}
