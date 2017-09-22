<?php

namespace App\Http\Controllers;

use PDF;
use App\Model\Bill;
use App\Model\BillDetail;
use App\Model\StudentAccount;
use App\Model\StudentParent;
use DB;

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

        $pdf =  PDF::loadView('report.bill-payment', $value, [], [
            'title' => 'bill-slip ('.$bill->BILL_NO.')',
            'author' => '',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'format' => 'Letter',
            ]);

        return $pdf->stream('bill-slip('.$bill->BILL_NO.').pdf');
    }


    public function getStudentCard($sid){
        ini_set('memory_limit', '128M');

        // $parent = StudentParent::find($pid); 
        $student = StudentAccount::find($sid);             
        $value = [
            // 'parent'=>$parent,
            'student'=>$student
        ];

        $pdf =  PDF::loadView('report.student-card', $value, [], [
            'title' => 'student-card ('.$student->SA_ID.')',
            'author' => '',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'format' => 'Letter',
            ]);
        return $pdf->stream('student-card('.$student->SA_ID.').pdf');
        // return view('report.student-card');
    }

    public function getParentCard($pid){
        ini_set('memory_limit', '128M');

        $parent = StudentParent::find($pid); 
        $student = StudentAccount::find($parent->SA_ID);             
        $value = [
            'parent'=>$parent,
            'student'=>$student
        ];

        $pdf =  PDF::loadView('report.parent-card', $value, [], [
            'title' => 'parent-card ('.$parent->SP_ID.')',
            'author' => '',
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
            'format' => 'Letter',
            ]);
        return $pdf->stream('parent-card('.$parent->SP_ID.').pdf');
        // return view('report.parent-card');
    }
}