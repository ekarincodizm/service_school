
<style>
    p {
        font-size : 90%;
    }
</style>


@if($bill->BILL_REGISTER_STATUS != '' && $bill->BILL_REGISTER_STATUS != 'A' && $bill->BILL_ROOM_TYPE != '1')
    <div style="height:50px;"></div>
@else
    <div style="height:30px;"></div>
@endif
       

<table style="width: 100%;">
	<tbody>
		<tr>
            <td style="width: 33%;"></td>
            <td style="width: 34%; text-align:center;" valign="middle">
                <h4 style="color:#FFF;">ใบเสร็จรับเงิน</h4>
            </td>
            <td style="width: 33%;">

            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 20%;">
                            <p style="color:#FFF;">เลขที่&nbsp;&nbsp;</p>
                        </td>
                        <td style="width: 80%">
                            <h4>{{$bill->RECEIPT_NO}}</h4>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20%;">
                            <p style="color:#FFF;">วันที่</p>
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

@if($bill->BILL_REGISTER_STATUS != '' && $bill->BILL_REGISTER_STATUS != 'A' && $bill->BILL_ROOM_TYPE != '1')
    <div style="height:10px;"></div>
@else
@endif

<table style="width: 100%;">
	<tbody>
		<tr>
			<td valign="bottom" style="text-align:left; width: 15%; height:40px;">
                <p style="color:#FFF;">ได้รับเงินจาก</p>
            </td>
            <td valign="bottom" style="text-align:center; width: 50%;">
                <h4>{{$studentAccount->SA_TITLE_NAME_TH.'&nbsp;'.$studentAccount->SA_FIRST_NAME_TH.'&nbsp;&nbsp;&nbsp;'.$studentAccount->SA_LAST_NAME_TH}}</h4>
            </td>
            <td valign="bottom" style="text-align:left;  width: 15%;">
                <p style="color:#FFF;">ห้อง</p>
            </td>
            <td valign="bottom" style="text-align:center;">
                <h4>
                    @if($studentAccount->SA_G3_ROOM_ID != null)
                        {{$studentAccount->g3Room->ROOM_NAME}} 
                    @elseif($studentAccount->SA_G2_ROOM_ID != null)
                        {{$studentAccount->g2Room->ROOM_NAME}} 
                    @elseif($studentAccount->SA_G1_ROOM_ID != null)
                        {{$studentAccount->g1Room->ROOM_NAME}} 
                    @elseif($studentAccount->SA_READY_ROOM_ID != null)
                       {{$studentAccount->readyRoom->ROOM_NAME}} 
                    @else
                        -
                    @endif

                </h4>
            </td>
        </tr>
	</tbody>
</table>
<?php $count = 1; ?>

@if($bill->BILL_REGISTER_STATUS != '' && $bill->BILL_REGISTER_STATUS != 'A' && $bill->BILL_ROOM_TYPE != '1')
    <div style="height:100px;">
@else
    <div style="height:200px;">
@endif


    <table style="width: 100%; border: 1px #FFF;  border-spacing: 0px;">
        <tbody>
            <tr>
                <td style="color:#FFF; width: 10%; text-align:center; border-right: 1px  #FFF; border-bottom: 1px  #FFF;" valign="middle"> <p>ลำดับที่</p> </td>
                <td style="color:#FFF; width: 70%; text-align:center; border-right: 1px  #FFF; border-bottom: 1px  #FFF;" valign="middle"> <p>รายการ</p> </td>
                <td style="color:#FFF; width: 20%; text-align:center; border-bottom: 1px  #FFF;" valign="middle"> <p>จำนวนเงิน</p> </td>
            </tr>

            @foreach ($billDetails as $index =>$billDetail)
                @if($billDetail->BD_TERM_FLAG == 'Y')

                    @if($bill->BILL_ROOM_TYPE != '1')

                        @if($bill->BILL_REGISTER_STATUS == 'SM')
                            <tr>
                                <td style="text-align:center; border-right: 1px  #FFF; " valign="middle"> <p style="color:#FFF"><?php echo $count; $count++; ?></p> </td>
                                <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"> <p><font color="#FFF">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ภาคเรียนที่ </font>{{$bill->BILL_TERM}} <font color="#FFF">ปีการศึกษา </font>{{$bill->BILL_YEAR}}</p> </td>
                                <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                            </tr>
                        @else
                            <tr>
                                <td style="text-align:center; border-right: 1px  #FFF; " valign="middle"> <p style="color:#FFF"><?php echo $count; $count++; ?></p> </td>
                                <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"> <p><font color="#FFF">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ภาคเรียนที่ </font>{{$bill->BILL_TERM}} <font color="#FFF">ปีการศึกษา </font>{{$bill->BILL_YEAR}}</p> </td>
                                <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                            </tr>
                        @endif		
                    
                    @else
                        <tr>
                            <td style="text-align:center; border-right: 1px  #FFF; " valign="middle"> <p><?php echo $count; $count++; ?></p> </td>
                            <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"> <p>&nbsp;{{$billDetail->BD_REMARK}} ภาคเรียนที่ {{$bill->BILL_TERM}} ปีการศึกษา {{$bill->BILL_YEAR}}</p> </td>
                            <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                        </tr>
                    @endif
                @endif	
            @endforeach

            @if($bill->BILL_REGISTER_STATUS != '' && $bill->BILL_REGISTER_STATUS != 'A' && $bill->BILL_ROOM_TYPE != '1')
                <tr>
                    <td style="text-align:center; border-right: 1px  #FFF; " valign="middle"></td>
                    <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"></td>
                    <td style="text-align:right; " valign="middle"> <p>{{number_format($billPriceNoMain)}} -&nbsp;</p> </td>
                </tr>
            @else
           

                @foreach ($billDetails as $index =>$billDetail)
                    @if(isset($billDetail->subject) && $billDetail->subject->SUBJECT_ORDER != null  && $billDetail->BD_TERM_FLAG == 'N')
                        @if(!isset($billDetail->subject->SUBJECT_CODE))
                            <tr>
                                <td style="text-align:center; border-right: 1px  #FFF; " valign="middle"> <p><?php echo $count; $count++; ?></p> </td>
                                <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"> <p>&nbsp;{{$billDetail->subject->SUBJECT_NAME}}</p> </td>
                                <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                            </tr>
                        @endif		
                    @endif	
                @endforeach

                @foreach ($billDetails as $index =>$billDetail)

                    @if(isset($billDetail->subject)  && $billDetail->BD_TERM_FLAG == 'N')
                        @if(isset($billDetail->subject->SUBJECT_CODE))
                            <tr>
                                <td style="text-align:center; border-right: 1px  #FFF; " valign="middle"> <p><?php echo $count; $count++; ?></p> </td>
                                <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"> <p>&nbsp;{{$billDetail->subject->SUBJECT_CODE}} : {{$billDetail->subject->SUBJECT_NAME}}</p> </td>
                                <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                            </tr>
                        @endif		
                    @endif
                
                @endforeach

                @foreach ($billDetails as $index =>$billDetail)
                    @if(isset($billDetail->subject) && $billDetail->subject->SUBJECT_ORDER == null && $billDetail->BD_TERM_FLAG == 'N')
                        @if(!isset($billDetail->subject->SUBJECT_CODE))
                            <tr>
                                <td style="text-align:center; border-right: 1px  #FFF; " valign="middle"> <p><?php echo $count; $count++; ?></p> </td>
                                <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"> <p>&nbsp;{{$billDetail->subject->SUBJECT_NAME}}</p> </td>
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
                                <td style="text-align:center; border-right: 1px  #FFF; " valign="middle"> <p><?php echo $count; $count++; ?></p> </td>
                                <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"> <p>&nbsp;{{$billDetail->BD_REMARK}}</p> </td>
                                <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                            </tr>
                        @endif
                    @endif
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<table style="width: 100%; border: 1px #FFF;  border-spacing: 0px;">   
    <tbody> 
        <tr>
            <td style="width: 10%; text-align:center; border-right: 1px #FFF; border-top: 1px #FFF;" valign="middle"> <p style="color:#FFF;">รวม</p> </td>
            <td style="width: 70%; text-align:center; border-right: 1px #FFF; border-top: 1px #FFF;" valign="middle"> <b><p>({{App\Http\Controllers\UtilController\StringUtil::convertNumberToText($billPrice)}})</p></b> </td>
            <td style="text-align:right; width: 20%; border-top: 1px #FFF;" valign="middle"> <p><b>{{number_format($billPrice)}} -&nbsp;</p></b> </td>
        </tr>
    </tbody>
</table>

<!-- <table style="width: 100%;">
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

@endif -->