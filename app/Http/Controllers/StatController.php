<?php

namespace App\Http\Controllers;

use DB;
use App\Http\Controllers\UtilController\DateUtil;


class StatController extends Controller{
    
    public function getIndex() {
        return response ()->json ( [
            'status' => 'ok',
        ] );
    }

    public function getRoomStudent() {

       $roomStudentValue = array();

       $currentDate = DateUtil::getCurrentYear().DateUtil::getCurrentMonth().DateUtil::getCurrentDay();

       $roomStudentSql = "SELECT r.ROOM_ID, r.ROOM_NAME, COUNT(cr.CR_ID) AS STUDENT_ROOM
       FROM ROOM r
       LEFT JOIN CLASS_ROOM cr ON (cr.ROOM_ID = r.ROOM_ID AND cr.USE_FLAG = 'Y')
       LEFT JOIN BILL_DETAIL bd ON (bd.CR_ID = cr.CR_ID)
       LEFT JOIN BILL b ON (b.BILL_ID = bd.BILL_ID)
       WHERE r.USE_FLAG = 'Y'
       AND b.BILL_STATUS <> 'C'
       AND ".$currentDate." BETWEEN bd.BD_START_LEARN AND bd.BD_END_LEARN
       GROUP BY r.ROOM_ID" ;

       $roomStudent = DB::select(DB::raw($roomStudentSql));

       $roomStudentEmptySql = "SELECT r.ROOM_ID, r.ROOM_NAME
       FROM ROOM r
       WHERE r.USE_FLAG = 'Y'";

       if(count($roomStudent) > 0){

        $roomStudentEmptySql.= "AND r.ROOM_ID NOT IN (";

        foreach ($roomStudent as $key => $value) {
            if($key > 0){
                $roomStudentEmptySql.= ",";
            }
            $roomStudentEmptySql.= $value->ROOM_ID;
        }
        $roomStudentEmptySql.= ")";
       }

       $roomStudentEmpty = DB::select(DB::raw($roomStudentEmptySql));
       
       foreach ($roomStudent as $key => $value) {
            $roomStudentValue[$key] = array(
                                    'roomId' => $value->ROOM_ID,
                                    'roomName' => $value->ROOM_NAME,
                                    'studentNum' => $value->STUDENT_ROOM
                                    );
       }

       foreach ($roomStudentEmpty as $key => $value) {
        $roomStudentValue[$key+count($roomStudent)] = array(
                                'roomId' => $value->ROOM_ID,
                                'roomName' => $value->ROOM_NAME,
                                'studentNum' => 0
                                );
        }    

        return response()->json($roomStudentValue);
      
    }

    public function getRoomTypeStudent() {

        $currentDate = DateUtil::getCurrentYear().DateUtil::getCurrentMonth().DateUtil::getCurrentDay();

        $roomTypeStudentSql = "SELECT rt.RT_ID, rt.RT_NAME, COUNT(rt.RT_ID) AS STUDENT_COUNT
        FROM ROOM_TYPE rt
        LEFT JOIN CLASS_ROOM cr ON (cr.RT_ID = rt.RT_ID AND cr.USE_FLAG = 'Y')
        LEFT JOIN BILL_DETAIL bd ON (bd.CR_ID = cr.CR_ID)
        LEFT JOIN BILL b ON (b.BILL_ID = bd.BILL_ID)
        WHERE b.BILL_STATUS <> 'C'
        AND ".$currentDate." BETWEEN bd.BD_START_LEARN AND bd.BD_END_LEARN
        GROUP BY RT_ID
        UNION
        SELECT rt.RT_ID, rt.RT_NAME, 0
        FROM ROOM_TYPE rt
        WHERE rt.RT_ID NOT IN (
            SELECT rt.RT_ID
            FROM ROOM_TYPE rt
            LEFT JOIN CLASS_ROOM cr ON (cr.RT_ID = rt.RT_ID AND cr.USE_FLAG = 'Y')
            LEFT JOIN BILL_DETAIL bd ON (bd.CR_ID = cr.CR_ID)
            LEFT JOIN BILL b ON (b.BILL_ID = bd.BILL_ID)
            WHERE b.BILL_STATUS <> 'C'
            AND ".$currentDate." BETWEEN bd.BD_START_LEARN AND bd.BD_END_LEARN
        )
        ORDER BY RT_ID ASC" ;
 
        $roomTypeStudent = DB::select(DB::raw($roomTypeStudentSql));

        return response()->json($roomTypeStudent);

    }

}