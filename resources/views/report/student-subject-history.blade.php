<style>

@page {
	header: page-header;
	footer: page-footer;
}


</style>


<h2 style="text-align: center; font-weight: bold; margin: 0;">ประวัติของนักเรียน </h2>
<h3 style="text-align: center; font-weight: bold; margin: 0;">{{$student->SA_TITLE_NAME_TH}} {{$student->SA_FIRST_NAME_TH}} {{$student->SA_LAST_NAME_TH}} </h3>
<br>
 <table style="border: 1px solid; width:100%; text-align: center; border-spacing: 0px; border-bottom: 0px solid;">
	<thead>
		<tr >
			<th style="width: 10%; height:50px; border-right: 1px solid; border-bottom: 1px solid;">#</th>
			<th style="width: 90%; border-right: 1px solid; border-bottom: 1px solid;">วิชา</th>
		</tr>
	</thead>
	<tbody>
	<?php 
        $term = "";
        $year = "";
    ?>
	@if(count($bills) > 0)
		{{ $i = 1 }}
		@foreach ($bills as $bill)
			@if ($term != $bill->BD_TERM || $year!= $bill->BD_YEAR)
                <tr>
                    <td colspan="3" style="background-color: #EEE;padding-left: 10px;height:20px;text-align: left;border-bottom: 1px solid;">
                        ภาคเรียนที่ {{$bill->BD_TERM}} ปีการศึกษา {{$bill->BD_YEAR}}
                    </td>
                </tr> 
                <?php 
                    $term = $bill->BD_TERM;
                    $year = $bill->BD_YEAR;
                ?>
            @endif
			<tr>
				<td style="width: 10%; text-align: center; border-right: 1px solid;border-bottom: 1px solid;">{{$i++}}</td>
				<td style="width: 90%;text-align: left; border-right: 1px solid; border-bottom: 1px solid;">&nbsp;&nbsp;{{$bill->SUBJECT_CODE}} - {{$bill->SUBJECT_NAME}}</td>
			</tr>

		@endforeach
	@else
		<tr>
			<td colspan="3" style="border-bottom: 1px solid;">ไม่พบข้อมูล</td>
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
