
<style>
    p {
        font-size : 90%;
    }
</style>

@if($bill->BILL_REGISTER_STATUS != '' && $bill->BILL_REGISTER_STATUS != 'A' && $bill->BILL_ROOM_TYPE != '1')
    <h4 style="position: absolute; left: 16cm; top: 2.3cm;">{{$bill->RECEIPT_NO}}</h4>
    <h4 style="position: absolute; left: 16cm; top: 3cm;">{{App\Http\Controllers\UtilController\DateUtil::convertDateStringToTextThai($bill->BILL_PAY_DATE)}}</h4>
    <h4 style="position: absolute; left: 2.5cm; top: 4.5cm;">{{$studentAccount->SA_TITLE_NAME_TH.'&nbsp;'.$studentAccount->SA_FIRST_NAME_TH.'&nbsp;&nbsp;&nbsp;'.$studentAccount->SA_LAST_NAME_TH}}</h4>
    <h4 style="position: absolute; left: 14cm; top: 4.5cm;">
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
    <p style="position: absolute; left: 2cm; top: 10.2cm;"><b>({{App\Http\Controllers\UtilController\StringUtil::convertNumberToText($billPrice)}})</b></p>
    <p style="position: absolute; left: 17.50cm; top: 10.2cm;"><b>{{number_format($billPrice)}} -</b></p>
@else
    <h4 style="position: absolute; left: 15.6cm; top: 1.3cm;">{{$bill->RECEIPT_NO}}</h4>
    <h4 style="position: absolute; left: 15.6cm; top: 1.9cm;">{{App\Http\Controllers\UtilController\DateUtil::convertDateStringToTextThai($bill->BILL_PAY_DATE)}}</h4>
    <h4 style="position: absolute; left: 1.8cm; top: 2.55cm;">{{$studentAccount->SA_TITLE_NAME_TH.'&nbsp;'.$studentAccount->SA_FIRST_NAME_TH.'&nbsp;&nbsp;&nbsp;'.$studentAccount->SA_LAST_NAME_TH}}</h4>
    <h4 style="position: absolute; left: 14.2cm; top: 2.55cm;">
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
    <p style="position: absolute; left: 3cm; top: 10.75cm;"><b>({{App\Http\Controllers\UtilController\StringUtil::convertNumberToText($billPrice)}})</b></p>
    <p style="position: absolute; left: 17.35cm; top: 10.75cm;"><b>{{number_format($billPrice)}} -</b></p>

@endif

<?php $count = 1; ?>

@if($bill->BILL_REGISTER_STATUS != '' && $bill->BILL_REGISTER_STATUS != 'A' && $bill->BILL_ROOM_TYPE != '1')
    <div style="width:720px; position: absolute; left: -0.25cm; top: 6.55cm;">
@else
    <div style="width:720px; position: absolute; left: -0.25cm; top: 4.85cm;">
@endif
    <table style="width: 100%; border: 1px #FFF;  border-spacing: 0px;">
        <tbody>
            <tr>
                <td style="width:60px;" ></td>
                <td style="width:600px;" ></td>
                <td style="" ></td>
            </tr>
            @foreach ($billDetails as $index =>$billDetail)
                @if($billDetail->BD_TERM_FLAG == 'Y')

                    @if($bill->BILL_ROOM_TYPE != '1')

                        @if($bill->BILL_REGISTER_STATUS == 'SM')
                            <tr>
                                <td style="text-align:center; border-right: 1px  #FFF; " valign="middle"> <p style="color:#FFF"><?php echo $count; $count++; ?></p> </td>
                                <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"> <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$bill->BILL_TERM}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$bill->BILL_YEAR}}</p> </td>
                                <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                            </tr>
                        @else
                            <tr>
                                <td style="text-align:center; border-right: 1px  #FFF; " valign="middle"> <p style="color:#FFF"><?php echo $count; $count++; ?></p> </td>
                                <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"> <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$bill->BILL_TERM}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$bill->BILL_YEAR}}</p> </td>
                                <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
                            </tr>
                        @endif		
                    
                    @else
                        <tr>
                            <td style="text-align:center; border-right: 1px  #FFF; " valign="middle"> <p><?php echo $count; $count++; ?></p> </td>
                            <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"> <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$billDetail->BD_REMARK}} ภาคเรียนที่ {{$bill->BILL_TERM}} ปีการศึกษา {{$bill->BILL_YEAR}}</p> </td>
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
                                <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"> <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$billDetail->subject->SUBJECT_NAME}}</p> </td>
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
                                <td style="text-align:left; border-right: 1px  #FFF; " valign="middle"> <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$billDetail->subject->SUBJECT_CODE}} : {{$billDetail->subject->SUBJECT_NAME}}</p> </td>
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