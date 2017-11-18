<?php

namespace App\Http\Controllers;

use PDF;
use App\Model\Bill;
use App\Model\BillDetail;
use App\Model\StudentAccount;
use App\Model\StudentParent;
use App\Model\User;
use App\Model\ClassRoom;
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

    public function getBillSlip($billNo){
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

        $pdf =  PDF::loadView('report.bill-slip', $value, [], [
            'title' => 'bill-slip ('.$bill->BILL_NO.')',
            'author' => '',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 15,
            'margin_right' => 15,
            'format' => 'Letter',
            ]);

        return $pdf->stream('bill-slip('.$bill->BILL_NO.').pdf');
    }


    public function getStudentCard($sid){
        ini_set('memory_limit', '128M');

        // $parent = StudentParent::find($pid); 
        $student = StudentAccount::find($sid);  
        $studentPicUrl = URL::asset('report/student-image/'.$sid.'');
        $value = [
            // 'parent'=>$parent,
            'student'=>$student,
            'studentPicUrl'=>$studentPicUrl
        ];

        $pdf =  PDF::loadView('report.student-card', $value, [], [
            'title' => 'student-card ('.$student->SA_ID.')',
            'author' => '',
            'margin_top' => 1,
            'margin_bottom' => 1,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => 'Legal',
            'orientation' => 'L'
            ]);
       return $pdf->stream('student-card('.$student->SA_ID.').pdf');
       //return view('report.student-card', $value);

       
    }

    public function getParentCard($pid){
        ini_set('memory_limit', '128M');

        $parent = StudentParent::find($pid); 
        $student = StudentAccount::find($parent->SA_ID);  
        
        $studentPicUrl = URL::asset('report/student-image/'.$parent->SA_ID.'');
        $parentPicUrl = URL::asset('report/parent-image/'.$pid.'');

        $value = [
            'parent'=>$parent,
            'student'=>$student,
            'studentPicUrl'=>$studentPicUrl,
            'parentPicUrl'=>$parentPicUrl

        ];

        $pdf =  PDF::loadView('report.parent-card', $value, [], [
            'title' => 'parent-card ('.$parent->SP_ID.')',
            'author' => '',
            'margin_top' => 1,
            'margin_bottom' => 1,
            'margin_left' => 1,
            'margin_right' => 1,
            'format' => 'Legal',
            'orientation' => 'L'
            ]);
        return $pdf->stream('parent-card('.$parent->SP_ID.').pdf');
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
        
        $reportSql = '  SELECT bd.BD_TERM, b.BILL_NO, s.SUBJECT_CODE ,s.SUBJECT_NAME, DATE_FORMAT(DATE_ADD(b.BILL_PAY_DATE, INTERVAL 543 YEAR), "%d/%m/%Y") AS PAY_DATE , Format(SUM(bd.BD_PRICE), "##,##0")  AS SUM_SUBJECT_PRICE 
                        FROM BILL b
                        LEFT JOIN BILL_DETAIL bd ON (bd.BILL_ID = b.BILL_ID)
                        LEFT JOIN SUBJECT s ON (bd.SUBJECT_ID = s.SUBJECT_ID)
                        WHERE b.BILL_STATUS = "P"
                        AND s.SUBJECT_TYPE = "S" ';

        $reportSql .= ' AND bd.BD_YEAR = '.$schoolYear;
        $reportSql .= ' GROUP BY  b.BILL_NO, bd.BD_TERM, s.SUBJECT_CODE
                        ORDER BY b.BILL_NO, bd.BD_TERM, s.SUBJECT_CODE ';

        $paymentReport = DB::select(DB::raw($reportSql));

        if(count($paymentReport) > 0){
            $sumTermPrice = DB::select(DB::raw('SELECT bd.BD_TERM , Format(SUM(bd.BD_PRICE), "##,##0")  AS SUM_TERM_PRICE
                                                FROM BILL b
                                                LEFT JOIN BILL_DETAIL bd ON (bd.BILL_ID = b.BILL_ID)
                                                LEFT JOIN SUBJECT s ON (s.SUBJECT_ID = bd.SUBJECT_ID)
                                                WHERE b.BILL_STATUS = "P"
                                                AND s.SUBJECT_TYPE = "S" 
                                                AND bd.BD_YEAR = '.$schoolYear.'
                                                GROUP BY  bd.BD_TERM ORDER BY  bd.BD_TERM'));

            $sumPrice = DB::select(DB::raw('SELECT Format(SUM(bd.BD_PRICE), "##,##0")  AS SUM_PRICE
                                            FROM BILL b
                                            LEFT JOIN BILL_DETAIL bd ON (bd.BILL_ID = b.BILL_ID)
                                            LEFT JOIN SUBJECT s ON (s.SUBJECT_ID = bd.SUBJECT_ID)
                                            WHERE b.BILL_STATUS = "P"
                                            AND s.SUBJECT_TYPE = "S"  AND bd.BD_YEAR = '.$schoolYear));
    
            
    
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

}