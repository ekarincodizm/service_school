<style>

@page {
	header: page-header;
	footer: page-footer;
}

th {
	text-align: center;
	vertical-align: middle !important;
}

table, th, td {
    border: 1px solid black;
}

table {
    border-collapse: collapse;
    table-layout:fixed;
}

</style>

<h2 style="text-align: center; font-weight: bold; margin: 0;">รายงานสรุปวิชาเรียน</h2>


<h3 style="text-align: center; font-weight: bold; margin: 0;"> {{$subjectName}} {{$term}}/{{$year}}</h3>


<br>

<table style="width:100%;">
	<thead>
		<tr >
            <th style="width: 10%;">วันที่</th>
			<th style="width: 10%;">ใบเสร็จ</th>
			<th style="width: 10%;"></th>
            <th style="width: 40%;">ชื่อ - สกุล</th>
            <th style="width: 10%;">ชำระเดือน</th>
            <th style="width: 20%;">จำนวนเงิน</th>
		</tr>
    </thead>
    <tbody>

    @if(count($values) == 0)
        <tr>
            <td colspan="6" style="text-align:center;">
                ไม่พบข้อมูล
            </td>
        </tr>
    @else

        @foreach ($values as $value)
            
            <tr>
                <td style="text-align:center;">{{$value->DATE}}</td>
                <td style="text-align:center;">{{$value->RECEIPT_NO}}</td>
                <td style="text-align:center;">{{$value->SA_STUDENT_ID}}</td>
                <td style="padding-left: 10px; height:30px; text-align:left;">{{$value->STUDENT_NAME}}</td>
                <td style="text-align:center;">{{$value->PAY_DATE}}</td>
                <td style="text-align:right;padding-right: 10px;">{{$value->BILL_DETAIL_PRICE}}</td>
                
            </tr>

        @endforeach

            <tr>
                <td style="text-align:right;padding-right: 10px;font-weight: bold;font-size: 18px;" colspan="5" >รวม</td>
                <td style="text-align:right;padding-right: 10px;font-weight: bold;font-size: 18px;">{{$subjectSum}}</td>
                
            </tr>
    @endif        
    </tbody>
</table>

<htmlpagefooter name="page-footer">

<table width="100%" style=" border: 0px !important;">
	<tr>
		<td style="text-align: left; border: 0px !important;">{{$currentTime}}</td>
		<td style="text-align: right; border: 0px !important;;">หน้า {PAGENO}/{nbpg}</td>
	</tr>
	<tr>
		<td style=" border: 0px !important;"><p>&nbsp;</p></td>
	</tr>
</table>

</htmlpagefooter>