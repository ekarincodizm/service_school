<?php

namespace App\Http\Controllers;

use PDF;
use App\Model\Bill;
use App\Model\StudentAccount;
use DB;

class ReportController extends Controller{
    
    public function getIndex() {
        return response ()->json ( [
            'status' => 'ok',
        ] );
    }

    public function getBillPayment($billNo){
        ini_set('memory_limit', '128M');
        
        $bill = $bill = Bill::where('BILL_NO', $billNo)->first();
        $studentAccount = StudentAccount::find($bill->SA_ID);

        $value = [
            'bill'=>$bill,
            'studentAccount'=>$studentAccount
        ];

        $pdf =  PDF::loadView('report.report-pdf', $value, [], [
            'title' => 'bill-payment ('.$bill->BILL_NO.')',
            'author' => '',
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 5,
            'margin_right' => 5,
            ]);

        return $pdf->stream('bill-payment('.$bill->BILL_NO.').pdf');
    }
}