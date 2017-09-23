<style>
    p {
        font-size : 120%;
    }
</style>
    
<table style="width: 100%;">
	<tbody>
		<tr>
			<td style="text-align:center;">
                <img style="width:120px;" src="{{ URL::asset('assets/images/logo-school-short.jpg')}}">
            </td>
        </tr>
        <tr>
            <td style="text-align:center;">
                <h2>โรงเรียนอนุบาลเปล่งประสิทธิ์ศรีนครินทร์</h2>
            </td>
        </tr>
	</tbody>
</table>

<table style="width: 100%;">
	<tbody>
		<tr>
            <td style="width: 33%;"></td>
            <td style="width: 34%; text-align:center;" valign="middle">
                <h2>ใบเสร็จรับเงิน</h2>
            </td>
            <td style="width: 33%;">

            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="width: 20%;">
                            <p>เลขที่&nbsp;&nbsp;</p>
                        </td>
                        <td style="width: 80%">
                            <h3>{{$bill->BILL_NO}}</h3>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 20%;">
                            <p>วันที่</p>
                        </td>
                        <td style="width: 80%">
                            <h3>{{App\Http\Controllers\UtilController\DateUtil::convertDateStringToTextThai($bill->BILL_PAY_DATE)}}</h3>
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
			<td valign="bottom" style="text-align:left; width: 10%; height:40px;">
                <p>ได้รับเงินจาก</p>
            </td>
            <td valign="bottom" style="text-align:center; width: 50%;">
                <h3>{{$studentAccount->SA_TITLE_NAME_TH.'&nbsp;'.$studentAccount->SA_FIRST_NAME_TH.'&nbsp;&nbsp;&nbsp;'.$studentAccount->SA_LAST_NAME_TH}}</h3>
            </td>
            <td valign="bottom" style="text-align:left;  width: 11%;">
                <p>รหัสประจำตัว</p>
            </td>
            <td valign="bottom" style="text-align:center;">
                <h3>{{$studentAccount->SA_STUDENT_ID}}</h3>
            </td>
        </tr>
	</tbody>
</table>

<table style="width: 100%; border: 1px solid;  border-spacing: 0px;">
	<tbody>
		<tr>
            <td style="width: 10%; text-align:center; border-right: 1px solid; border-bottom: 1px solid;" valign="middle"> <p>ลำดับที่</p> </td>
            <td style="width: 70%; text-align:center; border-right: 1px solid; border-bottom: 1px solid;" valign="middle"> <p>รายการ</p> </td>
            <td style="width: 20%; text-align:center; border-bottom: 1px solid;" valign="middle"> <p>จำนวนเงิน</p> </td>
        </tr>
        @foreach ($billDetails as $index =>$billDetail)
            <tr>
                <td style="text-align:center; border-right: 1px solid; " valign="middle"> <p>{{$index+1}}</p> </td>
                <td style="text-align:left; border-right: 1px solid; " valign="middle"> <p>&nbsp;{{$billDetail->classRoom->subject->SUBJECT_CODE}} : {{$billDetail->classRoom->subject->SUBJECT_NAME}}</p> </td>
                <td style="text-align:right; " valign="middle"> <p>{{number_format($billDetail->BD_PRICE)}} -&nbsp;</p> </td>
            </tr>
        @endforeach
        <tr>
            <td style="height:50px; border-right: 1px solid;"></td>
            <td style="border-right: 1px solid;"></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align:center; border-right: 1px solid; border-top: 1px solid;" valign="middle"> <p>รวม</p> </td>
            <td bgcolor="#c2c2d6" style="text-align:center; border-right: 1px solid; border-top: 1px solid;" valign="middle"> <p>({{App\Http\Controllers\UtilController\StringUtil::convertNumberToText($bill->BILL_TOTAL_PRICE)}})</p> </td>
            <td style="text-align:right; border-top: 1px solid;" valign="middle"> <p>{{number_format($bill->BILL_TOTAL_PRICE)}} -&nbsp;</p> </td>
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

<table style="width: 100%;">
	<tbody>
		<tr>
            <td style="width: 5%; text-align:left;" valign="middle">
                <p>โดย</p>
            </td>
            <td style="width: 3%; text-align:right;" valign="middle">
                <img style="width:15px;" src="{{ URL::asset('assets/images/square.jpg')}}">
            </td>
            <td style="width: 92%; text-align:left;" valign="middle">
                <p>&nbsp;โอน</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="width: 3%; text-align:right;" valign="middle">
                <img style="width:15px;" src="{{ URL::asset('assets/images/square.jpg')}}">
            </td>
            <td style="width: 92%; text-align:left;" valign="middle">
                <p>&nbsp;เช็ค ธนาคาร .................................................. สาขา ................................... 
                    เลขที่ .................................... วันที่ .............................
                </p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="width: 3%; text-align:right;" valign="middle">
                <img style="width:15px;" src="{{ URL::asset('assets/images/square.jpg')}}">
            </td>
            <td style="width: 92%; text-align:left;" valign="middle">
                <p>&nbsp;บัตรเครดิต</p>
            </td>
        </tr>
	</tbody>
</table>