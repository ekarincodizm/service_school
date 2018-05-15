<style>

@page {
	header: page-header;
	footer: page-footer;
}

th {
	text-align: center;
	vertical-align: middle !important;
}

table {
    border-collapse: collapse;
    table-layout:fixed;
}

</style>

<htmlpageheader name="page-header">
<div style="height:22px;"></div>
<table width="100%" style=" border: 0px !important;">
	<tr>
		<td style="border: 0px !important; width:85%" rowspan="3"></td>
		<td style="border: 0px !important; width:10%">หน้า</td>
		<td style="border: 0px !important;;">{PAGENO}/{nbpg}</td>
	</tr>
	<tr>
		<td style="border: 0px !important;;">วันที่พิมพ์</td>
		<td style="border: 0px !important;;">{{$currentDatePrint}}</td>
	</tr>
	<tr>
		<td style="border: 0px !important;;">เวลา</td>
		<td style="border: 0px !important;;">{{$currentTimePrint}}</td>
	</tr>
</table>

</htmlpageheader>  

<table style="width:100%; border-top:none; border-left:none; border-right:none;">
	<thead>
        <tr>
            <th colspan="8">
                <h2 style="text-align: center; font-weight: bold; margin: 0;">โรงเรียนอนุบาลเปล่งประสิทธิ์ศรีนครินทร์</h2>
		<h3 style="text-align: center; font-weight: bold; margin: 0;">รายงานสรุปการชำระเงิน</h3>
		<h3 style="text-align: center; font-weight: bold; margin: 0;">ประจำวันที่ {{$startDateThai}} ถึง {{$endDateThai}}</h3>
            </th>
        </tr>
        <tr>
            <th colspan="8" style="height:15px;"> </th>
        </tr>
        <tr>
	    	<th style="width: 5%; border-bottom: 1px solid black;">เลขทะเบียน</th>
		<th style="width: 25%; border-bottom: 1px solid black;">ชื่อ-สกุล</th>
		<th style="width: 10%; border-bottom: 1px solid black;">ชั้น</th>
		<th style="width: 15%; border-bottom: 1px solid black;">วันที่ชำระ</th>
		<th style="width: 10%; border-bottom: 1px solid black;">ภาคเรียน</th>
		<th style="width: 10%; border-bottom: 1px solid black;">ปีการศึกษา</th>
		<th style="width: 15%; border-bottom: 1px solid black;">จำนวนเงิน</th>
		<th style="width: 10%; border-bottom: 1px solid black;">ผู้รับเงิน</th>
	</tr>
	<tr>
            <th colspan="8" style="height:10px;"> </th>
        </tr>
    </thead>
    <tbody>
	    @if(count($bills) == 0)
	        <tr>
	            <td colspan="8" style="text-align:center;">
	                ไม่พบข้อมูล
	            </td>
	        </tr>
	        <tr>
	            <td colspan="8"  style="height:10px; border-bottom: 1px solid black;"></td>
	        </tr>
	   @else
	   
	   	@foreach ($bills as $bill)
		    	<tr>
		            <td>{{$bill->STUDENT_ID}}</td>
		            <td>{{$bill->STUDENT_NAME}}</td>
		            <td>{{$bill->ROOM_TYPE}}</td>
		            <td style="text-align:center;">{{$bill->PAY_DATE}}</td>
		            <td style="text-align:center;">{{$bill->BILL_TERM}}</td>
		            <td style="text-align:center;">{{$bill->BILL_YEAR}}</td>
		            <td style="text-align:right;">{{$bill->BILL_PRICE}}</td>
		            <td style="text-align:center;">{{$bill->USER_RECEIVED}}</td>
		        </tr>
	   	@endforeach
	   	
	   	<tr>
	   	 	<td colspan="8"  style="height:10px; border-bottom: 1px solid black;"></td>
	   	</tr>
	   	<tr>
	   	 	<td colspan="6" style="text-align: right; vertical-align: middle !important; height:40px; border-bottom: 1px solid black;"><h3 style="font-weight: bold; margin: 0;">รวมทั้งสิ้น</h3></td>
	   	 	<td style="text-align: right; vertical-align: middle !important; border-bottom: 1px solid black;"><h3 style="font-weight: bold; margin: 0;">{{$sumPrice[0]->SUM_PRICE}}</h3></td>
	   	 	<td style="text-align: center; vertical-align: middle !important; border-bottom: 1px solid black;"><h3 style="font-weight: bold; margin: 0;">บาท</h3></td>
	   	</tr>
	   	
	   @endif
    
    </tbody>
</table >






