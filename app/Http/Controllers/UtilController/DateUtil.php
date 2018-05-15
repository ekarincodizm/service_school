<?php

namespace App\Http\Controllers\UtilController;

use Carbon\Carbon;

class DateUtil {
	public static function genMonthList() {
		$monthList = array (
				'10' => 'ตุลาคม',
				'11' => 'พฤศจิกายน',
				'12' => 'ธันวาคม',
				'01' => 'มกราคม',
				'02' => 'กุมภาพันธ์',
				'03' => 'มีนาคม',
				'04' => 'เมษายน',
				'05' => 'พฤษภาคม',
				'06' => 'มิถุนายน',
				'07' => 'กรกฎาคม',
				'08' => 'สิงหาคม',
				'09' => 'กันยายน'
		);
		
		return $monthList;
	}
	public static function genAbbreviationMonthList() {
		$monthList = array (
				'10' => 'ต.ค.',
				'11' => 'พ.ย.',
				'12' => 'ธ.ค.',
				'01' => 'ม.ค.',
				'02' => 'ก.พ.',
				'03' => 'มี.ค.',
				'04' => 'เม.ย.',
				'05' => 'พ.ค.',
				'06' => 'มิ.ย.',
				'07' => 'ก.ค.',
				'08' => 'ส.ค.',
				'09' => 'ก.ย.'
		);
		
		return $monthList;
	}
	public static function genYearFive() {
		$currTime = Carbon::now ();
		$currYear = ($currTime->year) + 543;
		
		$dateUtil = new DateUtil ();
		
		return $dateUtil->genYearRang ( $currYear - 5, $currYear + 5 );
	}

	public static function getCurrentYear() {
		$currTime = Carbon::now ();
		$currYear = ($currTime->year);
		return $currYear;
	}
	
	public static function getCurrentThaiYear() {
		$currTime = Carbon::now ();
		$currYear = ($currTime->year) + 543;
		return $currYear;
	}

	public static function getCurrentThaiYear2Digit() {
		$currTime = Carbon::now ();
		$currYear = ($currTime->year) + 543;
		$currYear = substr($currYear,2,2);
		return $currYear;
	}
	
	public static function getCurrentMonth() {
		$currTime = Carbon::now ();
		$currMonth= $currTime->month;
		return $currMonth;
	}
	
	public static function getBeforeCurrentMonth2Digit() {
		$currTime = Carbon::now ();
		$currMonth= ($currTime->month)-1;
		if($currMonth== 0){
			$currMonth = 12;
		}else if($currTime->month == 10){
			$currMonth = 10;
		}
		return (str_pad((String)$currMonth, 2, "0", STR_PAD_LEFT));
	}
	
	public static function getBeforeCurrentMonthName() {
		return DateUtil::genAbbreviationMonthList()[DateUtil::getBeforeCurrentMonth2Digit()];
	}
	
	public static function getCurrentMonth2Digit() {
		$currTime = Carbon::now ();
		$currMonth= ($currTime->month);
		return (str_pad((String)$currMonth, 2, "0", STR_PAD_LEFT));
	}
	
	public static function getCurrentMonthName() {
		return DateUtil::genAbbreviationMonthList()[DateUtil::getCurrentMonth2Digit()];
	}
	public static function getCurrentFullMonthName() {
		return DateUtil::genMonthList()[DateUtil::getCurrentMonth2Digit()];
	}
	
	public function genYearRang($startYear, $endYear) {
		$yearRang = array ();
		
		for($i = $startYear; $i <= $endYear; $i ++) {
			$yearRang [$i] = $i;
		}
		return $yearRang;
	}
	
	public static function getCurrentDay() {
		$currTime = Carbon::now ();
		$currDay= $currTime->day;
		return $currDay;
	}
	
	public static function getCurrentTime() {
		$currTime = Carbon::now ('Asia/Bangkok');
		$currTime = (str_pad((String)$currTime->hour, 2, "0", STR_PAD_LEFT)).':'.(str_pad((String)$currTime->minute, 2, "0", STR_PAD_LEFT));
		
		return $currTime;
	}
	
	public static function getCurrentTimeWithSec() {
		$currTime = Carbon::now ('Asia/Bangkok');
		$currTime = (str_pad((String)$currTime->hour, 2, "0", STR_PAD_LEFT)).':'.(str_pad((String)$currTime->minute, 2, "0", STR_PAD_LEFT)).':'.(str_pad((String)$currTime->second, 2, "0", STR_PAD_LEFT));
		
		return $currTime;
	}
	
	public static function getThaiYearLastMonth() {
		$currTime = Carbon::now ();
		$currMonth= ($currTime->month)-1;
		$currYear = ($currTime->year) + 543;
		if($currMonth == 0){
			$currYear= $currYear-1;
		}
		return $currYear;
	}
	
	public static function getMonth($month) {
		$currTime = Carbon::now ();
		$currMonth= $currTime->month;
		return $currMonth;
	}
	
	public static function getMonth2DigitLastMonth($month) {
		$currMonth= $month-1;
		if($currMonth== 0){
			$currMonth = 12;
		}else if($currMonth == 10){
			$currMonth = 10;
		}
		return (str_pad((String)$currMonth, 2, "0", STR_PAD_LEFT));
	}
	
	public static function getMonthNameLastMonth($month) {
		return DateUtil::genAbbreviationMonthList()[DateUtil::getMonth2DigitLastMonth($month)];
	}
	
	public static function getMonth2Digit($month) {

		return (str_pad((String)$month, 2, "0", STR_PAD_LEFT));
	}
	
	public static function getMonthName($month) {
		return DateUtil::genAbbreviationMonthList()[DateUtil::getMonth2Digit($month)];
	}

	//========================================
	//20107-08-24
	public static function convertDbToDate($dateString){
		return Carbon::parse($dateString);
	}

	public static function addDate($date, $dayForAdd){
		return $date->addDays($dayForAdd);
	}

	public static function countDaysBetweenDate($startDate, $endDate){
		return DateUtil::convertDbToDate($startDate)->diffInDays(DateUtil::convertDbToDate($endDate));
	}

	public static function getDisplaytoStore($date) {
		$dateNumber = str_replace("-","",$date);
		$dateFormat = substr($dateNumber,0,8);
		return $dateFormat;
	}
	
	public static function getStoretoDisplay($date) {
		$dateFormat = '';
		if($date != null && $date!= 0){
			$dateFormat = substr($date,6,2).'/'.substr($date,4,2).'/'.substr($date,0,4);
		}
		
		return $dateFormat;
	}

	public static function convertDateStringToTextThai($dateString){
		$day = intval(substr($dateString,6,2));
		$month = DateUtil::genMonthList()[substr($dateString,4,2)];
		$year = (substr($dateString,0,4))+543;
		return $day.' '.$month.' '.$year;
	} 
	
}



