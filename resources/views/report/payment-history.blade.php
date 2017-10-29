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

<h2 style="text-align: center; font-weight: bold; margin: 0;">รายงานสรุปการชำระเงิน</h2>
<h3 style="text-align: center; font-weight: bold; margin: 0;">ประจำปีการศึกษา {{$schoolYear}}</h3>

<br>

<table style="width:100%;">
	<thead>
		<tr >
            <th style="width: 10%; height:50px;">เลขที่บิล</th>
            <th style="width: 10%;">ระดับชั้น</th>
            <th style="width: 10%;">รหัสวิชา</th>
			<th style="width: 50%;">ชื่อวิชา</th>
			<th style="width: 10%;">วันที่ชำระเงิน</th>
            <th style="width: 10%;">จำนวนเงิน (บาท)</th>
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

        <?php 
            $term = "";
         ?>

        @foreach ($values as $value)

            @if ($term != $value->CR_TERM)
                <tr>
                    <td colspan="5" style="background-color: #EEE;padding-left: 10px; height:30px;">
                        ภาคเรียนที่ {{$value->CR_TERM}}
                    </td>
                        @foreach ($sumTermPrice as $price)
                            @if($price->CR_TERM == $value->CR_TERM)
                                <td colspan="1" style="background-color: #EEE;text-align:right;padding-right: 5px;">{{$price->SUM_TERM_PRICE}} -</td>
                            @endif
                        @endforeach
                </tr> 
                <?php 
                    $term = $value->CR_TERM;
                ?>
            @endif
            
            <tr>
                <td style="text-align:center;">{{$value->BILL_NO}}</td>
                <td style="text-align:center;">{{$value->RT_NAME}}</td>
                <td style="text-align:center;">{{$value->SUBJECT_CODE}}</td>
                <td style="padding-left: 10px; height:30px; text-align:left;">{{$value->SUBJECT_NAME}}</td>
                <td style="text-align:center;">{{$value->PAY_DATE}}</td>
                <td style="text-align:right;padding-right: 5px;">{{$value->SUM_SUBJECT_PRICE}} - </td>
            </tr>

        @endforeach

        <tr>
            <td colspan="1" style="background-color: #EEE;padding-right: 10px; height:30px; text-align:right;"> รวมทั้งหมด </td>
            <td colspan="4" style="background-color: #EEE;padding-right: 10px; text-align:center;"> {{ $sumPriceText }} </td>
            <td style="text-align:right; background-color: #EEE;padding-right: 5px;">{{ $sumPrice[0]->SUM_PRICE }} - </td>
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