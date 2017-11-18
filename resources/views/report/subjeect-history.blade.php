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

@if ($startSchoolYear != "null" && $endSchoolYear != "null")
    @if ($startSchoolYear == $endSchoolYear)
        <h3 style="text-align: center; font-weight: bold; margin: 0;">ประจำปีการศึกษา {{$startSchoolYear}}</h3>
    @else
        <h3 style="text-align: center; font-weight: bold; margin: 0;">ประจำปีการศึกษา {{$startSchoolYear}} ถึง {{$endSchoolYear}}</h3>	
    @endif
@elseif ($startSchoolYear != "null" && $endSchoolYear == "null")
    <h3 style="text-align: center; font-weight: bold; margin: 0;">ประจำปีการศึกษา {{$startSchoolYear}}</h3>		
@elseif ($startSchoolYear == "null" && $endSchoolYear != "null")
@elseif ($startSchoolYear == $endSchoolYear)
    <h3 style="text-align: center; font-weight: bold; margin: 0;">ประจำปีการศึกษา {{$startSchoolYear}}</h3>
@endif

<br>

<table style="width:100%;">
	<thead>
		<tr >
            <th style="width: 20%;">รหัสวิชา</th>
			<th style="width: 60%;">ชื่อวิชา</th>
			<th style="width: 20%;">จำนวนการลงทะเบียน (ครั้ง)</th>
		</tr>
    </thead>
    <tbody>

    @if(count($values) == 0)
        <tr>
            <td colspan="3" style="text-align:center;">
                ไม่พบข้อมูล
            </td>
        </tr>
    @endif

        <?php 
            $term = "";
            $year = "";
         ?>

        @foreach ($values as $value)

            @if ($term != $value->BD_TERM || $year!= $value->BD_YEAR)
                <tr>
                    <td colspan="4" style="background-color: #EEE;padding-left: 10px; height:30px;">
                        ภาคเรียนที่ {{$value->BD_TERM}} ปีการศึกษา {{$value->BD_YEAR}}
                    </td>
                </tr> 
                <?php 
                    $term = $value->BD_TERM;
                    $year = $value->BD_YEAR;
                ?>
            @endif
            
            <tr>
                <td style="text-align:center;">{{$value->SUBJECT_CODE}}</td>
                <td style="padding-left: 10px; height:30px; text-align:left;">{{$value->SUBJECT_NAME}}</td>
                <td style="text-align:center;">{{$value->SUM_SUBJECT}}</td>
            </tr>

        @endforeach
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