<?php

namespace App\Http\Controllers;

use App\Model\StudentAccount;
use App\Model\StudentParent;
use App\Model\Bill;
use URL;

class ImageController extends Controller{
    
    public function getIndex() {
        return response ()->json ( [
            'status' => 'ok',
        ] );
    }

    //student-image/...
    public function getStudentImage($sid){
        
        $student = StudentAccount::find($sid);  
        $data = $student->SA_PICTURE; 

        if($data != null && $data != ''){
            header("Content-type: image/gif");
            echo base64_decode($data);
            exit;
        }else{
            $file = 'assets/images/no-image.jpg';
            $type = 'image/jpg';
            header('Content-Type:'.$type);
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }

    }

    //student-father-image/...
    public function getStudentFatherImage($sid){
        
        $student = StudentAccount::find($sid);  
        $data = $student->SA_FATHER_PICTURE; 

        if($data != null && $data != ''){
            header("Content-type: image/gif");
            echo base64_decode($data);
            exit;
        }else{
            $file = 'assets/images/no-image.jpg';
            $type = 'image/jpg';
            header('Content-Type:'.$type);
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }
        
    }

    //student-mother-image/...
    public function getStudentMotherImage($sid){
        
        $student = StudentAccount::find($sid);  
        $data = $student->SA_MOTHER_PICTURE; 

        if($data != null && $data != ''){
            header("Content-type: image/gif");
            echo base64_decode($data);
            exit;
        }else{
            $file = 'assets/images/no-image.jpg';
            $type = 'image/jpg';
            header('Content-Type:'.$type);
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }
        
    }

    //student-mother-image/...
    public function getStudentEmergencyImage($sid){
        
        $student = StudentAccount::find($sid);  
        $data = $student->SA_EMERGENCY_PICTURE; 

        if($data != null && $data != ''){
            header("Content-type: image/gif");
            echo base64_decode($data);
            exit;
        }else{
            $file = 'assets/images/no-image.jpg';
            $type = 'image/jpg';
            header('Content-Type:'.$type);
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }
        
    }

    //parent-image/...
    public function getParentImage($sid){
        
        $parent = StudentParent::find($sid);  
        $data = $parent->SP_PICTURE; 

        if($data != null && $data != ''){
            header("Content-type: image/gif");
            echo base64_decode($data);
            exit;
        }else{
            $file = 'assets/images/no-image.jpg';
            $type = 'image/jpg';
            header('Content-Type:'.$type);
            header('Content-Length: ' . filesize($file));
            readfile($file);
        }
        
    }

    //bill-image/...
    public function getBillImage($id){
        
        $bill = Bill::find($id);  
        $data = $bill->BILL_PIC; 

        header("Content-type: image/jpg");
        echo base64_decode($data);
        exit;
        
    }

    //bill-pic-default
    public function getBillPicDefault(){
        $file = 'assets/images/bill-pic-default.png';
        $type = 'image/png';
        header('Content-Type:'.$type);
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }


}