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
            <th colspan="10">
                <h2 style="text-align: center; font-weight: bold; margin: 0;">รายงานชื่อผู้ปกครองโรงเรียนอนุบาลเปล่งประสิทธิ์ศรีนครินทร์</h2>
                <h3 style="text-align: center; font-weight: bold; margin: 0;">ปีการศึกษา {{$schoolYear}}</h3>
                <h3 style="text-align: center; font-weight: bold; margin: 0;">ระดับชั้น {{$roomTypeName}} ห้อง {{$roomName}}</h3>
            </th>
        </tr>
        <tr>
            <th colspan="10" style="text-align: left;">
                <p style="font-size:80%;">{{$currentTime}}</p>
            </th>
		</tr>
		<tr class="border-on">
            <th style="width: 3%;">ที่</th>
			<th style="width: 15%;">ชื่อ-สกุล</th>
			<th style="width: 7%;">ความสัมพันธ์</th>
			<th style="width: 19%;">ที่อยู่ เลขที่ ถนน</th>
			<th style="width: 9%;">แขวง</th>
			<th style="width: 9%;">เขต</th>
			<th style="width: 10%;">จังหวัด</th>
			<th style="width: 5%;">รหัส</th>
			<th style="width: 8%;">อาชีพ</th>
			<th style="width: 15%;">โทรศัพท์, มือถือ</th>
		</tr>
    </thead>
    <tbody>

    @if(count($studentAccountes) == 0)
        <tr>
            <td colspan="10" style="text-align:center;">
                ไม่พบข้อมูล
            </td>
        </tr>
    @endif

    <?php  $i = 1; ?>

    @foreach ($studentAccountes as $studentAccount)

        <tr>
            <td style="text-align:center; background-color:#E6E6E6;">
                {{$i++}}
            </td>

            <td style="padding-left: 10px; background-color:#E6E6E6;" colspan="9">
                {{$studentAccount->SA_TITLE_NAME_TH}} {{$studentAccount->SA_FIRST_NAME_TH}} {{$studentAccount->SA_LAST_NAME_TH}}
            </td>
        </tr> 

         @if(count($studentAccount->parent) == 0)
            <tr>
                <td colspan="10" style="text-align:center;">
                    ไม่พบข้อมูลผู้ปกครอง
                </td>
            </tr>
        @endif

        @foreach ($studentAccount->parent as $parent)

            <tr>
                <td></td>
                <td style="padding-left: 10px;">
                    {{$parent->SP_TITLE_NAME}} {{$parent->SP_FIRST_NAME}} {{$parent->SP_LAST_NAME}} 
                </td>
                <td style="text-align:center;">
                    {{$parent->SP_RELATION}}
                </td>
                <td style="padding-left: 10px;">
                    {{$parent->SP_ADDRESS}}
                </td>
                <td style="padding-left: 10px;">
                    {{$parent->District->DISTRICT_NAME}}
                </td>
                <td style="padding-left: 10px;">
                    {{$parent->Amphur->AMPHUR_NAME}}
                </td>
                <td style="padding-left: 10px;">
                    {{$parent->Province->PROVINCE_NAME}}
                </td>
                <td style="padding-left: 10px;">
                    {{$parent->Amphur->POSTCODE}}
                </td>
                <td style="padding-left: 10px;">
                 @if($parent->SP_JOB != "อื่น ๆ") {{$parent->SP_JOB}} @elseif($parent->SP_JOB == "อื่น ๆ" && $parent->SP_JOB_REMARK != null) {{$parent->SP_JOB_REMARK}} @endif
                </td>
                <td style="padding-left: 10px;">
                    {{$parent->SP_HOME_TEL}}, {{$parent->SP_TEL}}
                </td>
            </tr>
            
        @endforeach

    @endforeach
    </tbody>
</table>