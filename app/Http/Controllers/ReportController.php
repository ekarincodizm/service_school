<?php

namespace App\Http\Controllers;

use PDF;
use App\Model\Bill;
use App\Model\BillDetail;
use App\Model\StudentAccount;
use App\Model\StudentParent;
use App\Model\User;
use App\Model\ClassRoom;
use App\Model\Room;
use App\Model\Subject;
use DB;
use URL;
use App\Http\Controllers\UtilController\DateUtil;
use App\Http\Controllers\UtilController\StringUtil;
use App\Model\Province;
use App\Model\Amphur;
use App\Model\District;

class ReportController extends Controller{
    
    public function getIndex() {
        return response ()->json ( [
            'status' => 'ok',
        ] );
    }

    public function getBillPayment($billNo){
        ini_set('memory_limit', '128M');
        
        $bill = Bill::where('BILL_NO', $billNo)->first();
        $studentAccount = StudentAccount::find($bill->SA_ID);
        $billDetails = BillDetail::select('BILL_DETAIL.*')
                        ->where("BILL_ID", $bill->BILL_ID)
                        ->leftJoin('SUBJECT', 'BILL_DETAIL.SUBJECT_ID', '=', 'SUBJECT.SUBJECT_ID')
                        ->orderBy('SUBJECT.SUBJECT_CODE')
                        ->get();       
                        
        $value = [
            'bill'=>$bill,
            'billDetails'=>$billDetails,
            'studentAccount'=>$studentAccount
        ];

        $pdf =  PDF::loadView('report.bill-payment', $value, [], [
            'title' => 'bill-payment ('.$bill->BILL_NO.')',
            'author' => '',
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
            ]);

        return $pdf->stream('bill-payment('.$bill->BILL_NO.').pdf');
    }

    public function getBillSlip($billNo, $billDetailIds){
        ini_set('memory_limit', '128M');
        
        $bill = Bill::where('BILL_NO', $billNo)->first();
        $studentAccount = StudentAccount::find($bill->SA_ID);
        $billDetails = BillDetail::select('BILL_DETAIL.*')
                        ->where("BILL_ID", $bill->BILL_ID);

        if($billDetailIds != 'null' && $billDetailIds != null){
            $billDetails = $billDetails->whereRaw('BILL_DETAIL.BD_ID IN ('.$billDetailIds.')');
        }

        $billDetails =  $billDetails->leftJoin('SUBJECT', 'BILL_DETAIL.SUBJECT_ID', '=', 'SUBJECT.SUBJECT_ID')
                        ->orderByRaw('case when BILL_DETAIL.BD_TERM_FLAG = "Y" then 1 else 0 end,case when SUBJECT.SUBJECT_ORDER is null then 1 else 0 end, SUBJECT.SUBJECT_ORDER, SUBJECT.SUBJECT_CODE')
                        ->get();

        $billPrice = 0;
        $billPriceNoMain = 0;

        foreach ($billDetails as $billDetail) {
            $billPrice += $billDetail->BD_PRICE;
            if($billDetail->BD_TERM_FLAG != 'Y'){
                $billPriceNoMain += $billDetail->BD_PRICE;
            }
        }
                        
        
         $value = [
               'bill'=>$bill,
               'billDetails'=>$billDetails,
               'studentAccount'=>$studentAccount,
               'billPrice'=>$billPrice,
               'billPriceNoMain'=>$billPriceNoMain
         ];

         $pdf =  PDF::loadView('report.bill-slip-2', $value, [], [
            'title' => 'bill-slip ('.$bill->BILL_NO.')',
            'author' => '',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'format' => [139.7, 228.6],
            'orientation' => 'L'
            ]);

        return $pdf->stream('bill-slip('.$bill->BILL_NO.').pdf');
    }


    public function getStudentCard($sid){
        ini_set('memory_limit', '128M');

        // $parent = StudentParent::find($pid); 
        $student = StudentAccount::find($sid);  
        $studentPicUrl = URL::asset('image/student-image/'.$sid.'');
        $value = [
            // 'parent'=>$parent,
            'student'=>$student,
            'studentPicUrl'=>$studentPicUrl
        ];

        $pdf =  PDF::loadView('report.student-card-v2', $value, [], [
            'title' => 'student-card ('.$student->SA_NICK_NAME_TH.')',
            'author' => '',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
            'format' => [54, 86]
            ]);

       return $pdf->stream('student-card('.$student->SA_NICK_NAME_TH.').pdf');
       //return view('report.student-card', $value);

    }

    public function getParentCard($pid){
        ini_set('memory_limit', '128M');

        $parent = StudentParent::find($pid); 
        $student = StudentAccount::find($parent->SA_ID);  
        
        $studentPicUrl = URL::asset('image/student-image/'.$parent->SA_ID.'');
        $parentPicUrl = URL::asset('image/parent-image/'.$pid.'');

        $value = [
            'parent'=>$parent,
            'student'=>$student,
            'studentPicUrl'=>$studentPicUrl,
            'parentPicUrl'=>$parentPicUrl

        ];

        $pdf =  PDF::loadView('report.parent-card-v2', $value, [], [
            'title' => 'parent-card ('.$parent->SP_TITLE_NAME.$parent->SP_FIRST_NAME.' '.$parent->SP_LAST_NAME.')',
            'author' => '',
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
            'format' => [54, 86]
            ]);
        return $pdf->stream('parent-card('.$parent->SP_TITLE_NAME.$parent->SP_FIRST_NAME.' '.$parent->SP_LAST_NAME.').pdf');
        // return view('report.parent-card');
    }

    public function getParentReport($pid){
        ini_set('memory_limit', '128M');

        $parent = StudentParent::find($pid); 
        $student = StudentAccount::find($parent->SA_ID);  
        
        $studentPicUrl = URL::asset('report/student-image/'.$parent->SA_ID.'');
        $parentPicUrl = URL::asset('report/parent-image/'.$pid.'');

        $province = Province::find($parent->SP_PROVINCE);
        $amphur = Amphur::find($parent->SP_AMPHUR);
        $district = District::find($parent->SP_DISTRICT);
        $address = 'ต.'.$district->DISTRICT_NAME.' อ.'.$amphur->AMPHUR_NAME.' จ.'.$province->PROVINCE_NAME.' '.$amphur->POSTCODE;

        $value = [
            'parent'=>$parent,
            'student'=>$student,
            'address'=>$address,
            'studentPicUrl'=>$studentPicUrl,
            'parentPicUrl'=>$parentPicUrl

        ];

        $pdf =  PDF::loadView('report.parent-report', $value, [], [
            'title' => 'parent-report ('.$parent->SP_ID.')',
            'author' => '',
            'margin_top' => 10,
            'margin_bottom' => 15,
            'margin_left' => 10,
            'margin_right' => 10,
            'format' => 'A4',
            ]);
        return $pdf->stream('parent-report('.$parent->SP_ID.').pdf');
        // return view('report.parent-card');
    }

    public function getStudentImage($sid){
        
        $student = StudentAccount::find($sid);  
        $data = $student->SA_PICTURE;

        header("Content-type: image/gif");
        echo base64_decode($data);
        exit;
        
    }

    public function getParentImage($pid){
        
        $parent = StudentParent::find($pid); 
        $data = $parent->SP_PICTURE;

        header("Content-type: image/gif");
        echo base64_decode($data);
        exit;
        
    }

    public function getStudentSubjectHistory($sid){
        ini_set('memory_limit', '128M');

        // $parent = StudentParent::find($pid); 
        $currentTime = 'วันที่ '.DateUtil::getCurrentDay().' '.DateUtil::genMonthList()[DateUtil::getCurrentMonth2Digit()].' พ.ศ. '.DateUtil::getCurrentThaiYear().' '.DateUtil::getCurrentTime().' น.';
        $student = StudentAccount::find($sid);         

        // $bill = Bill::where('SA_ID', $sid)->where('USE_FLAG',"Y")->where('BILL_STATUS',"P")->get();

        $reportSql = 'SELECT bd.BD_YEAR, bd.BD_TERM,s.SUBJECT_CODE ,s.SUBJECT_NAME
        FROM BILL b
        INNER JOIN BILL_DETAIL bd ON (bd.BILL_ID = b.BILL_ID and b.BILL_STATUS = "P")
        INNER JOIN SUBJECT s ON (bd.SUBJECT_ID = s.SUBJECT_ID and s.SUBJECT_TYPE = "S")
        WHERE 1 = 1 
        AND b.SA_ID = '.$sid ;
        $reportSql .= ' GROUP BY  bd.BD_YEAR, bd.BD_TERM, s.SUBJECT_CODE
        ORDER BY bd.BD_YEAR, bd.BD_TERM,s.SUBJECT_CODE';

        $bill = DB::select(DB::raw($reportSql));

        $value = [
            'bills'=>$bill,
            'student'=>$student,
            'currentTime'=>$currentTime
        ];

        $pdf =  PDF::loadView('report.student-subject-history', $value, [], [
            'title' => 'student-subject-history ('.$sid.')',
            'author' => '',
            'margin_top' => 10,
            'margin_bottom' => 15,
            'margin_left' => 10,
            'margin_right' => 10,
            'format' => 'A4',
            ]);
        return $pdf->stream('student-subject-history('.$sid.').pdf');
        // return view('report.student-subject-history', $value);
    }
        
    public function getSubjectReport($startSchoolYear, $endSchoolYear, $userPrint){

        $reportSql = 'SELECT bd.BD_YEAR, bd.BD_TERM, s.SUBJECT_CODE ,s.SUBJECT_NAME, COUNT(s.SUBJECT_CODE) AS SUM_SUBJECT
                        FROM BILL b
                        LEFT JOIN BILL_DETAIL bd ON (bd.BILL_ID = b.BILL_ID)
                        LEFT JOIN SUBJECT s ON (bd.SUBJECT_ID = s.SUBJECT_ID)
                        WHERE b.BILL_STATUS = "P"
                        AND s.SUBJECT_TYPE = "S" ';

        if($startSchoolYear != "null" && $endSchoolYear != "null"){
            $reportSql .= ' AND bd.BD_YEAR >= '.$startSchoolYear;
            $reportSql .= ' AND bd.BD_YEAR <= '.$endSchoolYear;
        }else if($startSchoolYear != "null" && $endSchoolYear == "null"){
            $reportSql .= ' AND bd.BD_YEAR = '.$startSchoolYear;
        }else if($startSchoolYear == "null" && $endSchoolYear != "null"){
            $reportSql .= ' AND bd.BD_YEAR = '.$endSchoolYear;
        }

        $reportSql .= ' GROUP BY  bd.BD_YEAR, bd.BD_TERM, s.SUBJECT_CODE 
                        ORDER BY bd.BD_YEAR, bd.BD_TERM, s.SUBJECT_CODE';

        $subjectReport = DB::select(DB::raw($reportSql));

        $user = User::find($userPrint);
        $currentTime = 'วันที่ '.DateUtil::getCurrentDay().' '.DateUtil::genMonthList()[DateUtil::getCurrentMonth2Digit()].' พ.ศ. '.DateUtil::getCurrentThaiYear().' '.DateUtil::getCurrentTime().' น.';

        $value = [
            'values'=>$subjectReport,
            'user'=>$user,
            'startSchoolYear'=>$startSchoolYear,
            'endSchoolYear'=>$endSchoolYear,
            'currentTime'=>$currentTime
        ];

        $pdf =  PDF::loadView('report.subjeect-history', $value, [], [
            'title' => 'subject-history',
            'author' => '',
            'margin_top' => 10,
            'margin_bottom' => 15,
            'margin_left' => 10,
            'margin_right' => 10,
            ]);

        return $pdf->stream('subject-history.pdf');

    }

    public function getPaymentReport($schoolYear, $userPrint){
        
        $user = User::find($userPrint);
        $currentTime = 'วันที่ '.DateUtil::getCurrentDay().' '.DateUtil::genMonthList()[DateUtil::getCurrentMonth2Digit()].' พ.ศ. '.DateUtil::getCurrentThaiYear().' '.DateUtil::getCurrentTime().' น.';
        
        $reportSql = '  SELECT bd.BD_TERM, b.BILL_NO, s.SUBJECT_ID, s.SUBJECT_CODE ,s.SUBJECT_NAME, bd.BD_REMARK, DATE_FORMAT(DATE_ADD(b.BILL_PAY_DATE, INTERVAL 543 YEAR), "%d/%m/%Y") AS PAY_DATE , Format(SUM(bd.BD_PRICE), "##,##0")  AS SUM_SUBJECT_PRICE 
                        FROM BILL b
                        LEFT JOIN BILL_DETAIL bd ON (bd.BILL_ID = b.BILL_ID)
                        LEFT JOIN SUBJECT s ON (s.SUBJECT_ID = bd.SUBJECT_ID) 
                        WHERE b.BILL_STATUS = "P"
                        AND bd.BD_YEAR = '.$schoolYear.'
                        GROUP BY  bd.BD_YEAR, bd.BD_TERM, b.BILL_NO, bd.BD_REMARK
                        ORDER BY bd.BD_YEAR, bd.BD_TERM, b.BILL_NO, bd.BD_REMARK';

        $paymentReport = DB::select(DB::raw($reportSql));

        if(count($paymentReport) > 0){
            $sumTermPrice = DB::select(DB::raw('SELECT bd.BD_TERM , Format(SUM(bd.BD_PRICE), "##,##0")  AS SUM_TERM_PRICE
                                                FROM BILL b
                                                LEFT JOIN BILL_DETAIL bd ON (bd.BILL_ID = b.BILL_ID)
                                                LEFT JOIN SUBJECT s ON (s.SUBJECT_ID = bd.SUBJECT_ID) 
                                                WHERE b.BILL_STATUS = "P"
                                                AND bd.BD_YEAR = '.$schoolYear.' GROUP BY  bd.BD_TERM ORDER BY  bd.BD_TERM'));

            $sumPrice = DB::select(DB::raw('SELECT Format(SUM(bd.BD_PRICE), "##,##0")  AS SUM_PRICE
                                            FROM BILL b
                                            LEFT JOIN BILL_DETAIL bd ON (bd.BILL_ID = b.BILL_ID)
                                            LEFT JOIN SUBJECT s ON (s.SUBJECT_ID = bd.SUBJECT_ID) 
                                            WHERE b.BILL_STATUS = "P"
                                            AND bd.BD_YEAR = '.$schoolYear));
    
            
    
            $value = [
                'values'=>$paymentReport,
                'sumTermPrice'=>$sumTermPrice,
                'sumPrice' => $sumPrice,
                'sumPriceText' => StringUtil::convertNumberToText(str_replace(",","",$sumPrice[0]->SUM_PRICE)),
                'user'=>$user,
                'schoolYear'=>$schoolYear,
                'currentTime'=>$currentTime
            ];
        }else{
            $value = [
                'values'=>$paymentReport,
                'user'=>$user,
                'schoolYear'=>$schoolYear,
                'currentTime'=>$currentTime
            ];
        }

        

        $pdf =  PDF::loadView('report.payment-history', $value, [], [
            'title' => 'subject-history',
            'author' => '',
            'margin_top' => 10,
            'margin_bottom' => 15,
            'margin_left' => 10,
            'margin_right' => 10,
            ]);

        return $pdf->stream('payment-history.pdf');

    }

    public function getPaymentReport2($startDate, $endDate, $userPrint){
        
        $startDateThai = DateUtil::convertDateStringToTextThai($startDate);
        $endDateThai = DateUtil::convertDateStringToTextThai($endDate);
        $userPrint = User::find($userPrint);
        $currentDatePrint = DateUtil::getCurrentDay().'/'.DateUtil::getCurrentMonth2Digit().'/'.DateUtil::getCurrentThaiYear();
        $currentTimePrint = DateUtil::getCurrentTimeWithSec();

         $reportSql = "SELECT sa.SA_STUDENT_ID as STUDENT_ID, 
                CONCAT(sa.SA_TITLE_NAME_TH, ' ' , sa.SA_FIRST_NAME_TH, ' ',  sa.SA_LAST_NAME_TH) as STUDENT_NAME,
                (CASE WHEN b.BILL_ROOM_TYPE = '1' THEN 'เตรียมอนุบาล' WHEN b.BILL_ROOM_TYPE = '2'  THEN 'อนุบาล 1' WHEN b.BILL_ROOM_TYPE = '3'  THEN 'อนุบาล 2' WHEN b.BILL_ROOM_TYPE = '4'  THEN 'อนุบาล 3' ELSE 'ไม่ได้ระบุ' END) AS ROOM_TYPE,
                CONCAT(SUBSTRING(b.BILL_PAY_DATE, 7, 2), '/',SUBSTRING(b.BILL_PAY_DATE, 5, 2), '/', CONVERT(SUBSTRING(b.BILL_PAY_DATE, 1, 4) ,UNSIGNED INTEGER)  + 543) AS PAY_DATE,
                (CASE WHEN b.BILL_TERM IS NULL THEN 'ไม่ได้ระบุ' ELSE b.BILL_TERM END) AS BILL_TERM,
                (CASE WHEN b.BILL_YEAR IS NULL THEN 'ไม่ได้ระบุ' ELSE b.BILL_YEAR END) AS BILL_YEAR,
                FORMAT(b.BILL_TOTAL_PRICE,2) AS BILL_PRICE,
                u.USER_LOGIN AS USER_RECEIVED
                FROM BILL b
                LEFT JOIN STUDENT_ACCOUNT sa ON (sa.SA_ID = b.SA_ID)
                LEFT JOIN USER u ON (u.USER_ID = b.UPDATE_BY)
                WHERE b.BILL_STATUS = 'P'
                AND BILL_PAY_DATE BETWEEN '".$startDate."' AND '".$endDate."' 
                ORDER BY b.BILL_PAY_DATE";
                
        $sumPriceSql = "SELECT FORMAT(SUM(b.BILL_TOTAL_PRICE),2) AS SUM_PRICE
                        FROM BILL b
                        WHERE b.BILL_STATUS = 'P'
                        AND BILL_PAY_DATE BETWEEN '".$startDate."' AND '".$endDate."'";
        
        $bill = DB::select(DB::raw($reportSql));
        $sumPrice = DB::select(DB::raw($sumPriceSql));

        $value = [
            'bills'=>$bill,
            'sumPrice'=>$sumPrice,
            'startDateThai'=>$startDateThai,
            'endDateThai'=>$endDateThai,
            'userPrint'=>$userPrint,
            'currentDatePrint'=>$currentDatePrint,
            'currentTimePrint'=>$currentTimePrint
            
        ];

        $pdf =  PDF::loadView('report.payment-history2', $value, [], [
            'title' => 'payment-history',
            'author' => '',
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
            'format' => 'A4',
            ]);
        return $pdf->stream('payment-history.pdf');
        
    }

    public function getStudentNameReport($schoolYear, $schoolTerm, $roomType, $roomId, $userPrint){
        ini_set('memory_limit', '-1');
        $studentAccount;
        $roomTypeName;
        $roomName;
        $user = User::find($userPrint);
        $currentTime = 'วันเวลาที่พิมพ์รายงาน '.DateUtil::getCurrentDay().' '.DateUtil::genMonthList()[DateUtil::getCurrentMonth2Digit()].' พ.ศ. '.DateUtil::getCurrentThaiYear().' '.DateUtil::getCurrentTime().' น.';

        if($roomType == '1'){ //เตรียมอนุบาล
            $roomTypeName = "เตรียมอนุบาล";
            $studentAccount = StudentAccount::where('SA_READY_YEAR', $schoolYear)->where('SA_READY_ROOM_ID', $roomId)->orderBy('SA_STUDENT_ID')->get();
        }else if($roomType == '2'){ //อนุบาล 1
            $roomTypeName = "อนุบาล 1";
            $studentAccount = StudentAccount::where('SA_G1_YEAR', $schoolYear)->where('SA_G1_ROOM_ID', $roomId)->orderBy('SA_STUDENT_ID')->get();
        }else if($roomType == '3'){ //อนุบาล 2
            $roomTypeName = "อนุบาล 2";
            $studentAccount = StudentAccount::where('SA_G2_YEAR', $schoolYear)->where('SA_G2_ROOM_ID', $roomId)->orderBy('SA_STUDENT_ID')->get();
        }else if($roomType == '4'){ //อนุบาล 3
            $roomTypeName = "อนุบาล 3";
            $studentAccount = StudentAccount::where('SA_G3_YEAR', $schoolYear)->where('SA_G3_ROOM_ID', $roomId)->orderBy('SA_STUDENT_ID')->get();
        }


        $roomName = Room::find($roomId)->ROOM_NAME;

        $value = [
            'schoolYear'=>$schoolYear,
            'roomTypeName'=>$roomTypeName,
            'studentAccountes'=>$studentAccount,
            'roomName' => $roomName,
            'currentTime'=>$currentTime
        ];

        $pdf =  PDF::loadView('report.student-name', $value, [], [
            'title' => 'student-name-report',
            'author' => '',
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
            ]);

        return $pdf->stream('student-name-report.pdf');

    }

    public function getParentNameReport($schoolYear, $schoolTerm, $roomType, $roomId, $userPrint){
        ini_set('memory_limit', '-1');
        $studentAccount;
        $roomTypeName;
        $roomName;
        $user = User::find($userPrint);
        $currentTime = 'วันเวลาที่พิมพ์รายงาน '.DateUtil::getCurrentDay().' '.DateUtil::genMonthList()[DateUtil::getCurrentMonth2Digit()].' พ.ศ. '.DateUtil::getCurrentThaiYear().' '.DateUtil::getCurrentTime().' น.';

        if($roomType == '1'){ //เตรียมอนุบาล
            $roomTypeName = "เตรียมอนุบาล";
            $studentAccount = StudentAccount::where('SA_READY_YEAR', $schoolYear)->where('SA_READY_ROOM_ID', $roomId)->orderBy('SA_STUDENT_ID')->get();
        }else if($roomType == '2'){ //อนุบาล 1
            $roomTypeName = "อนุบาล 1";
            $studentAccount = StudentAccount::where('SA_G1_YEAR', $schoolYear)->where('SA_G1_ROOM_ID', $roomId)->orderBy('SA_STUDENT_ID')->get();
        }else if($roomType == '3'){ //อนุบาล 2
            $roomTypeName = "อนุบาล 2";
            $studentAccount = StudentAccount::where('SA_G2_YEAR', $schoolYear)->where('SA_G2_ROOM_ID', $roomId)->orderBy('SA_STUDENT_ID')->get();
        }else if($roomType == '4'){ //อนุบาล 3
            $roomTypeName = "อนุบาล 3";
            $studentAccount = StudentAccount::where('SA_G3_YEAR', $schoolYear)->where('SA_G3_ROOM_ID', $roomId)->orderBy('SA_STUDENT_ID')->get();
        }


        $roomName = Room::find($roomId)->ROOM_NAME;

        $value = [
            'schoolYear'=>$schoolYear,
            'roomTypeName'=>$roomTypeName,
            'studentAccountes'=>$studentAccount,
            'roomName' => $roomName,
            'currentTime'=>$currentTime
        ];

        $pdf =  PDF::loadView('report.parent-name', $value, [], [
            'title' => 'parent-name-report',
            'author' => '',
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
            'format' => 'A4-L',
            ]);

        return $pdf->stream('parent-name-report.pdf');

    }

    public function getSubjectPaymentReport($subjectId, $term, $year, $userPrint){

        $reportSql = 'SELECT DATE_FORMAT(DATE_ADD(b.UPDATE_DATE, INTERVAL 543 YEAR), "%d/%m/%y") AS DATE,
                        b.RECEIPT_NO, sa.SA_STUDENT_ID, CONCAT(sa.SA_TITLE_NAME_TH, " " , sa.SA_FIRST_NAME_TH, " ",  sa.SA_LAST_NAME_TH) as STUDENT_NAME, 
                        CONCAT(CONVERT(SUBSTRING(b.BILL_PAY_DATE, 5, 2),UNSIGNED INTEGER), "/", SUBSTRING(CONVERT(CONVERT(SUBSTRING(b.BILL_PAY_DATE, 1, 4) ,UNSIGNED INTEGER)  + 543, CHAR), 3, 2)) AS PAY_DATE,
                        FORMAT(bd.BD_PRICE,2) AS BILL_DETAIL_PRICE , bd.BD_PRICE
                        FROM BILL_DETAIL bd
                        LEFT JOIN BILL b ON (b.BILL_ID = bd.BILL_ID)
                        LEFT JOIN STUDENT_ACCOUNT sa ON (sa.SA_ID = b.SA_ID)
                        WHERE b.BILL_STATUS = "P" ';

        if($subjectId != "null" && $subjectId != "null"){
            $reportSql .= ' AND bd.SUBJECT_ID = '.$subjectId;
        }else if($term != "null" && $term == "null"){
            $reportSql .= ' AND b.BILL_TERM = '.$term;
        }else if($year == "null" && $year != "null"){
            $reportSql .= ' AND b.BILL_YEAR = '.$year;
        }

        $reportSql .= ' ORDER BY b.BILL_PAY_DATE ';

        

        $subjectReport = DB::select(DB::raw($reportSql));

        $user = User::find($userPrint);
        $subject = Subject::find($subjectId);
        $currentTime = 'วันที่ '.DateUtil::getCurrentDay().' '.DateUtil::genMonthList()[DateUtil::getCurrentMonth2Digit()].' พ.ศ. '.DateUtil::getCurrentThaiYear().' '.DateUtil::getCurrentTime().' น.';
        $sumPrice = "";
        if(count($subjectReport) > 0){
            $reportSumSql = 'SELECT FORMAT(SUM(a.BD_PRICE),2) AS SUM_PRICE FROM ( '.$reportSql.' ) a';
            $subjectSumReport = DB::select(DB::raw($reportSumSql));
            $sumPrice = $subjectSumReport[0]->SUM_PRICE;
        }
        
        $value = [
            'values'=>$subjectReport,
            'user'=>$user,
            'year'=>substr($year,2),
            'term'=>$term,
            'subjectName'=>$subject->SUBJECT_NAME,
            'subjectSum'=>$sumPrice,
            'currentTime'=>$currentTime
        ];

        $pdf =  PDF::loadView('report.subject-payment', $value, [], [
            'title' => 'subject-payment',
            'author' => '',
            'margin_top' => 10,
            'margin_bottom' => 15,
            'margin_left' => 10,
            'margin_right' => 10,
            ]);

        return $pdf->stream('subject-payment.pdf');

    }

}