<?php

namespace App\Http\Controllers;

use DB;
use App\Http\Controllers\UtilController\DateUtil;
use App\Model\Room;

class StatController extends Controller{
    
    public function getIndex() {
        return response ()->json ( [
            'status' => 'ok',
        ] );
    }

    public function getRoomStudent() {

        $roomStudentValue = array();

        $startYear = DateUtil::getCurrentThaiYear()-2;
        $endYear = DateUtil::getCurrentThaiYear();

        $allRooms = Room::where('USE_FLAG', 'Y')->get();

        for($i = $startYear; $i <= $endYear; $i++){

            foreach ($allRooms as $key => $value) {

                $numberRoomStudentOfYearQuery = '   SELECT COUNT(sa.SA_ID) AS SA_COUNT
                                                    FROM STUDENT_ACCOUNT sa
                                                    WHERE (sa.SA_READY_ROOM_ID = '.$value->ROOM_ID.' AND sa.SA_READY_YEAR = '.$i.')
                                                    OR (sa.SA_G1_ROOM_ID = '.$value->ROOM_ID.' AND sa.SA_G1_YEAR = '.$i.')
                                                    OR (sa.SA_G2_ROOM_ID = '.$value->ROOM_ID.' AND sa.SA_G2_YEAR = '.$i.')
                                                    OR (sa.SA_G3_ROOM_ID = '.$value->ROOM_ID.' AND sa.SA_G3_YEAR = '.$i.')';

                $numberRoomStudentOfYear =          DB::select(DB::raw($numberRoomStudentOfYearQuery));

                 $roomStudentValue[$i][$key] = array(
                                             'year' => $i,
                                             'name' => $value->ROOM_NAME,
                                             'number' => $numberRoomStudentOfYear[0]->SA_COUNT
                                         );

            }
            
        }

       

        return response()->json([
            'room'=>$allRooms, 
            'data' => $roomStudentValue, 
            'startYear' =>  $startYear, 
            'endYear' => $endYear, 
            'color'.$startYear => '#42A5F5',
            'color'.($startYear+1) => '#9CCC65',
            'color'.$endYear => '#FC44B5', ]);
      
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