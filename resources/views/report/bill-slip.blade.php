
<style>
    p {
        font-size : 90%;
    }
</style>

    <table style="width: 100%;">
	<tbody>
        @if($bill->BILL_REGISTER_STATUS != '' && $bill->BILL_REGISTER_STATUS != 'A' && $bill->BILL_ROOM_TYPE != '1')
            <tr>
                <td style="text-align:center;">
                    <img style="width:100px;" src="{{ URL::asset('assets/images/logo-school-short.jpg')}}">
                </td>
            </tr>
        @endif
        <tr>
            <td style="text-align:center;">
                <h3>โรงเรียนอนุบาลเปล่งประสิทธิ์ศรีนครินทร์</h3>
            </td>
        </tr>
	</tbody>
</table>

<table style="width: 100%;">
	<tbody>
		<tr>
            <td style="width: 33%;"></td>
            <td style="width: 34%; text-align:center;" valign="middle">
                <h4>ใบเสร็จรับเงิน</h4>
            </td>
            <td style="width: 33%;">

            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 20%;">
                            <p>เลขที่&nbsp;&nbsp;</p>
                        </td>
                        <td style="width: 80%">
                            <h4>{{$bill->BILL_NO}}</h4>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20%;">
                            <p>วันที่</p>
                        </td>
                        <td style="width: 80%">
                            <h4>{{App\Http\Controllers\UtilController\DateUtil::convertDateStringToTextThai($bill->BILL_PAY_DATE)}}</h4>
                        </td>
                    </tr>
                </tbody>
            </table>
            </td>
        </tr>
	</tbody>
</table>

<table style="width: 100%;">
	<tbody>
		<tr>
			<td valign="bottom" style="text-align:left; width: 15%; height:40px;">
                <p>ได้รับเงินจาก</p>
            </td>
            <td valign="bottom" style="text-align:center; width: 50%;">
                <h4>{{$studentAccount->SA_TITLE_NAME_TH.'&nbsp;'.$studentAccount->SA_FIRST_NAME_TH.'&nbsp;&nbsp;&nbsp;'.$studentAccount->SA_LAST_NAME_TH}}</h4>
            </td>
            <td valign="bottom" style="text-align:left;  width: 15%;">
                <p>รหัสประจำตัว</p>
            </td>
            <td valign="bottom" style="text-align:center;">
                <h4>{{$studentAccount->SA_STUDENT_ID}}</h4>
            </td>
        </tr>
	</tbody>
</table>
<?php $count = 1; ?>
<table style="width: 100%; border: 1px solid;  border-spacing: 0px;">
	<tbody>
		<tr>
            <td style="width: 10%; text-align:center; border-right: 1px solid; border-bottom: 1px solid;" valign="middle"> <p>ลำดับที่</p> </td>
            <td style="width: 70%; text-align:center; border-right: 1px solid; border-bottom: 1px solid;" valign="middle"> <p>รายการ</p> </td>
            <td style="width: 20%; text-align:center; border-bottom: 1px solid;" valign="middle"> <p>จำนวนเงิน</p> </td>
        </tr>

         @foreach ($billDetails as $index =>$billDetail)
            @if($billDetail->BD_TERM_FLAG == 'Y')
                @if($bill->BILL_REGISTER_STATUS == 'SM')
                    <tr>
                        <td style="text-align:center; border-right: 1px solid; " valign="middle"> <p><?php echo $count; $count++; ?></p> </td>
                        <td style="text-align:left; border-right: 1px solid; " valign="middle"> <p>&nbsp;{{$billDetail->BD_REMARK}} {{$billDetail->subject->SUBJECT_CODE}} : {{$billDetail->subject->SUBJECT_NAME}} ปีการศึกษา {{$bill->BILL_YEAR}}</p> </td>
                        <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                    </tr>
                @else
                    <tr>
                        <td style="text-align:center; border-right: 1px solid; " valign="middle"> <p><?php echo $count; $count++; ?></p> </td>
                        <td style="text-align:left; border-right: 1px solid; " valign="middle"> <p>&nbsp;{{$billDetail->BD_REMARK}} ภาคเรียนที่ {{$bill->BILL_TERM}} ปีการศึกษา {{$bill->BILL_YEAR}}</p> </td>
                        <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                    </tr>
                @endif		
            @endif	
        @endforeach

        @foreach ($billDetails as $index =>$billDetail)
            @if(isset($billDetail->subject) && $billDetail->subject->SUBJECT_ORDER != null  && $billDetail->BD_TERM_FLAG == 'N')
                @if(!isset($billDetail->subject->SUBJECT_CODE))
                    <tr>
                        <td style="text-align:center; border-right: 1px solid; " valign="middle"> <p><?php echo $count; $count++; ?></p> </td>
                        <td style="text-align:left; border-right: 1px solid; " valign="middle"> <p>&nbsp;{{$billDetail->subject->SUBJECT_NAME}}</p> </td>
                        <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                    </tr>
                @endif		
            @endif	
        @endforeach

        @foreach ($billDetails as $index =>$billDetail)

            @if(isset($billDetail->subject)  && $billDetail->BD_TERM_FLAG == 'N')
                @if(isset($billDetail->subject->SUBJECT_CODE))
                    <tr>
                        <td style="text-align:center; border-right: 1px solid; " valign="middle"> <p><?php echo $count; $count++; ?></p> </td>
                        <td style="text-align:left; border-right: 1px solid; " valign="middle"> <p>&nbsp;{{$billDetail->subject->SUBJECT_CODE}} : {{$billDetail->subject->SUBJECT_NAME}}</p> </td>
                        <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                    </tr>
                @endif		
            @endif
           
        @endforeach

        @foreach ($billDetails as $index =>$billDetail)
            @if(isset($billDetail->subject) && $billDetail->subject->SUBJECT_ORDER == null && $billDetail->BD_TERM_FLAG == 'N')
                @if(!isset($billDetail->subject->SUBJECT_CODE))
                    <tr>
                        <td style="text-align:center; border-right: 1px solid; " valign="middle"> <p><?php echo $count; $count++; ?></p> </td>
                        <td style="text-align:left; border-right: 1px solid; " valign="middle"> <p>&nbsp;{{$billDetail->subject->SUBJECT_NAME}}</p> </td>
                        <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                    </tr>
                @endif		
            @endif	
        @endforeach

        @foreach ($billDetails as $index =>$billDetail)
            @if(isset($billDetail->subject))
            @else
                @if($billDetail->BD_TERM_FLAG == 'N')
                    <tr>
                        <td style="text-align:center; border-right: 1px solid; " valign="middle"> <p><?php echo $count; $count++; ?></p> </td>
                        <td style="text-align:left; border-right: 1px solid; " valign="middle"> <p>&nbsp;{{$billDetail->BD_REMARK}}</p> </td>
                        <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                    </tr>
                @endif
            @endif
        @endforeach

        @if($bill->BILL_REGISTER_STATUS != '' && $bill->BILL_REGISTER_STATUS != 'A' && $bill->BILL_ROOM_TYPE != '1')
        
             @if(count($billDetails) < 3)
                <tr>
                    <td style="height:50px; border-right: 1px solid;"></td>
                    <td style="border-right: 1px solid;"></td>
                    <td></td>
                </tr>
            @endif
        
        @else
        
        
             @if(count($billDetails) < 9)
                <tr>
                    <td style="height:50px; border-right: 1px solid;"></td>
                    <td style="border-right: 1px solid;"></td>
                    <td></td>
                </tr>
            @endif
        
        @endif
         
        <tr>
            <td style="text-align:center; border-right: 1px solid; border-top: 1px solid;" valign="middle"> <p>รวม</p> </td>
            <td bgcolor="#c2c2d6" style="text-align:center; border-right: 1px solid; border-top: 1px solid;" valign="middle"> <p>({{App\Http\Controllers\UtilController\StringUtil::convertNumberToText($billPrice)}})</p> </td>
            <td style="text-align:right; border-top: 1px solid;" valign="middle"> <p>{{number_format($billPrice)}} -&nbsp;</p> </td>
        </tr>
	</tbody>
</table>

<table style="width: 100%;">
    <tbody>
		<tr> 
            <td style="height:10px;"> </td>
        </tr>
    </tbody>
</table>

 @if($bill->BILL_REGISTER_STATUS != '' && $bill->BILL_REGISTER_STATUS != 'A' && $bill->BILL_ROOM_TYPE != '1')

<table style="width: 100%;">
	<tbody>
		<tr>
            <td style="width: 7%; text-align:left;" valign="middle">
                <p>โดย</p>
            </td>
            <td style="width: 1%; text-align:right;" valign="middle">
                <img style="width:9px;" src="{{ URL::asset('assets/images/square.jpg')}}">
            </td>
            <td style="width: 92%; text-align:left;" valign="middle">
                <p>&nbsp;โอน</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right;" valign="middle">
                <img style="width:9px;" src="{{ URL::asset('assets/images/square.jpg')}}">
            </td>
            <td style="text-align:left;" valign="middle">
                <p>&nbsp;เช็ค ธนาคาร ............................................................................................ สาขา ....................................................................... 
                    เลขที่ ............................................. วันที่ .........................................
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right;" valign="middle">
                <img style="width:9px;" src="{{ URL::asset('assets/images/square.jpg')}}">
            </td>
            <td style="text-align:left;" valign="middle">
                <p>&nbsp;บัตรเครดิต</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:right;" valign="middle">
                <p>................................&nbsp;&nbsp;&nbsp;</p>
                <p>ผู้รับเงิน&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            </td>
        </tr>
	</tbody>
</table>

@else

<table style="width: 100%;">
	<tbody>
		<tr>
            <td style="width: 7%; text-align:left;" valign="middle">
                <p>โดย</p>
            </td>
            <td style="width: 1%; text-align:right;" valign="middle">
                <img style="width:9px;" src="{{ URL::asset('assets/images/square.jpg')}}">
            </td>
            <td style="width: 7%; text-align:left;" valign="middle">
                <p>&nbsp;เงินสด</p>
            </td>
            <td style="width: 1%; text-align:right;" valign="middle">
                <img style="width:9px;" src="{{ URL::asset('assets/images/square.jpg')}}">
            </td>
            <td style="width: 7%; text-align:left;" valign="middle">
                <p>&nbsp;บัตรเครดิต</p>
            </td>
            <td style="width: 4%; text-align:right;" valign="middle">
                <img style="width:9px;" src="{{ URL::asset('assets/images/square.jpg')}}">
            </td>
            <td style="width: 7%; text-align:left;" valign="middle">
                <p>&nbsp;โอน</p>
            </td>
            <td style="width: 66%;text-align:right;" valign="middle">
            <p>ผู้รับเงิน ................................&nbsp;&nbsp;&nbsp;</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:right;" valign="middle">
                <img style="width:9px;" src="{{ URL::asset('assets/images/square.jpg')}}">
            </td>
            <td colspan="6" style="text-align:left;" valign="middle">
                <p>&nbsp;เช็ค ธนาคาร ........................................................................................................ สาขา ............................................... 
                    เลขที่ ............................................. วันที่ ..........................................................
                </p>
            </td>
        </tr>
	</tbody>
</table>

@endif