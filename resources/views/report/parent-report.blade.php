<style>

@page {
	header: page-header;
	footer: page-footer;
}


</style>
<h2 style="text-align: center; font-weight: bold; margin: 0;">ข้อมูลผู้ปกครองของนักเรียน</h2>
<div style="text-align: center;padding-top:5px"><img width="150px" src="{{ $studentPicUrl }}" ></div>
<h3 style="text-align: center; font-weight: bold; margin: 0;padding-top:5px">{{$student->SA_STUDENT_ID}} {{$student->SA_TITLE_NAME_TH}} {{$student->SA_FIRST_NAME_TH}} {{$student->SA_LAST_NAME_TH}} </h3>
<br>
<table style="width:100%; text-align: center; border-spacing: 0px; border-bottom: 0px solid;padding-left:5%;font-size:	20px;">
	<tr>
		<td style=" width:8%" rowspan="11" ><img width="150px" src="{{ $parentPicUrl }}" ></td>
		<td style=" width:2%" rowspan="11" ></td>
		<td style=" width:10%;text-align: right;">ชื่อ-สกุล: </td>
		<td style=" width:35%;text-align: left;">{{$parent->SP_TITLE_NAME}} {{$parent->SP_FIRST_NAME}} {{$parent->SP_LAST_NAME}}</td>
		<td style=" width:10%;text-align: right;">ความสัมพันธ์: </td>
		<td style=" width:35%;text-align: left;">{{$parent->SP_RELATION}}</td>
	<tr>
	<tr>
	@if($parent->SA_FATHER_FOREIGNER_FLAG == "N")
		<td style=" width:10%;text-align: right;">เลขบัตรประชาชน: </td>
		<td style=" width:35%;text-align: left;" colspan = "3">{{$parent->SP_CITIZEN_CODE}}</td>
	@else
		<td style=" width:10%;text-align: right;">เลขหนังสือเดินทาง: </td>
		<td style=" width:35%;text-align: left;" colspan = "3">{{$parent->SP_FOREIGNER}}</td>
	@endif
	</tr>
	<tr>
		<td style=" width:10%;text-align: right;">ที่อยู่: </td>
		<td style=" width:35%;text-align: left;" colspan = "3">{{$address}}</td>
	<tr>
	<tr>
		<td style=" width:10%;text-align: right;">อาชีพ: </td>
		<td style=" width:35%;text-align: left;">
			@if($parent->SP_JOB == "อื่น ๆ")
				{{$parent->SP_JOB_REMARK}}
			@else
				{{$parent->SP_JOB}}
			@endif
		</td>
		<td style=" width:10%;text-align: right;">รายได้ต่อเดือน: </td>
		<td style=" width:35%;text-align: left;">{{$parent->SP_JOB_SALARY}}</td>
	<tr>
	<tr>
		<td style=" width:10%;text-align: right;">อีเมล์: </td>
		<td style=" width:35%;text-align: left;" colspan = "3">{{$parent->SP_EMAIL}}</td>
	<tr>

	<tr>
		<td style=" width:10%;text-align: right;">เบอร์โทรศัพท์มือถือ: </td>
		<td style=" width:35%;text-align: left;">{{$parent->SP_TEL}}</td>
		<td style=" width:10%;text-align: right;">เบอร์โทรศัพท์บ้าน: </td>
		<td style=" width:35%;text-align: left;">{{$parent->SP_HOME_TEL}}</td>
	<tr>
	
</table>
