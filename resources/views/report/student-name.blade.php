<style>

@page {
	header: page-header;
	footer: page-footer;
}

th {
	text-align: center;
	vertical-align: middle !important;
}

table, tr.border-on th, td {
    border: 1px solid black;
}

table {
    border-collapse: collapse;
    table-layout:fixed;
}

</style>

<htmlpageheader name="page-header">
<div style="height:10px;"></div>
<table width="100%" style=" border: 0px !important;">
	<tr>
		<td style="text-align: right; border: 0px !important;;">หน้า {PAGENO}/{nbpg}</td>
	</tr>
	<tr>
		<td style=" border: 0px !important;"><p>&nbsp;</p></td>
	</tr>
</table>

</htmlpageheader>  

<table style="width:100%; border-top:none; border-left:none; border-right:none;">
	<thead>
        <tr>
            <th colspan="6">
                <h2 style="text-align: center; font-weight: bold; margin: 0;">รายงานชื่อนักเรียนโรงเรียนอนุบาลเปล่งปรัสิทธิ์ศรีนครินทร์</h2>
                <h3 style="text-align: center; font-weight: bold; margin: 0;">ปีการศึกษา {{$schoolYear}}</h3>
                <h3 style="text-align: center; font-weight: bold; margin: 0;">ระดับชั้น {{$roomTypeName}} ห้อง {{$roomName}}</h3>
            </th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: left;">
                <p style="font-size:80%;">{{$currentTime}}</p>
            </th>
		</tr>
		<tr class="border-on">
            <th style="width: 5%;">ที่</th>
			<th style="width: 10%;">รหัส</th>
			<th style="width: 30%;">ชื่อ-สกุล</th>
			<th style="width: 15%;">วันเดือนปีเกิด</th>
			<th style="width: 10%;">อายุ</th>
			<th style="width: 30%;">โรงเรียนที่ต้องการศึกษาต่อ</th>
		</tr>
    </thead>
    <tbody>

    @if(count($studentAccountes) == 0)
        <tr>
            <td colspan="6" style="text-align:center;">
                ไม่พบข้อมูล
            </td>
        </tr>
    @endif

    <?php  $i = 1; ?>

    @foreach ($studentAccountes as $studentAccount)

        <tr>
            <td style="text-align:center;">
                {{$i++}}
            </td>

            <td style="text-align:center;">
                {{$studentAccount->SA_STUDENT_ID}}
            </td>

            <td style="padding-left: 10px;">
                {{$studentAccount->SA_TITLE_NAME_TH}} {{$studentAccount->SA_FIRST_NAME_TH}} {{$studentAccount->SA_LAST_NAME_TH}}
            </td>

            <td style="text-align:center;">
                {{$studentAccount->birthDay}}/{{$studentAccount->birthMonth}}/{{$studentAccount->birthYear}}
            </td>

            <td style="text-align:center;">
                {{$studentAccount->age}}
            </td>

            <td style="padding-left: 10px;">
                {{$studentAccount->futureSchoolName}}
            </td>

        </tr> 
            

    @endforeach

    </tbody>
</table>