<?php

namespace App\Http\Controllers;

use PDF;
use App\Model\Bill;
use App\Model\BillDetail;
use App\Model\StudentAccount;
use App\Model\StudentParent;
use DB;
use URL;
use App\Http\Controllers\UtilController\DateUtil;

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
                        ->join('CLASS_ROOM', 'BILL_DETAIL.CR_ID', '=', 'CLASS_ROOM.CR_ID')
                        ->join('SUBJECT', 'CLASS_ROOM.SUBJECT_ID', '=', 'SUBJECT.SUBJECT_ID')
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
                        ->join('CLASS_ROOM', 'BILL_DETAIL.CR_ID', '=', 'CLASS_ROOM.CR_ID')
                        ->join('SUBJECT', 'CLASS_ROOM.SUBJECT_ID', '=', 'SUBJECT.SUBJECT_ID')
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

        $bill = Bill::where('SA_ID', $sid)->where('USE_FLAG',"Y")->where('BILL_STATUS',"P")->get();

        $value = [
            'bills'=>$bill,
            'student'=>$student,
            'currentTime'=>$currentTime
        ];

        $pdf =  PDF::loadView('report.student-subject-history', $value, [], [
            'title' => 'student-subject-history ('.$sid.')',
            'author' => '',
            'margin_top' => 10,
            'margin_bottom' => 30,
            'margin_left' => 10,
            'margin_right' => 10,
            'format' => 'A4',
            ]);
        return $pdf->stream('student-subject-history('.$sid.').pdf');
        // return view('report.parent-card');
    }
        
}