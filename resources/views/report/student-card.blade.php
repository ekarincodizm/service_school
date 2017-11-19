<table style="width:8.6cm; border-spacing: 0px;">
	<tbody>
		<tr>
			<td style=" width:3cm; height:1.2cm; text-align:center;"><img style="width:2cm;" src="{{ URL::asset('assets/images/logo-school-short.jpg')}}"></td>
			<td style=" width:5.6cm; height:1.2cm; ">
				<h3>โรงเรียนอนุบาลเปล่งประสิทธิ์</h3>
			</td>
		</tr>
	</tbody>
</table>

<table style="width:8.6cm; border-spacing: 0px;">
	<tbody>
		<tr>
			<td style="width:5cm; height:3cm; text-align:center;"   valign="middle">
				<h3>น้อง{{$student->SA_NICK_NAME_TH}}</h3>
				<p>{{$student->SA_TITLE_NAME_TH}} {{$student->SA_FIRST_NAME_TH}} {{$student->SA_LAST_NAME_TH}}</p>
			</td>
			<td style="width:3.6cm; text-align:center;" valign="middle"><img width="2.2cm" height="3cm" src="{{ $studentPicUrl }}"></td>
		</tr>
		<tr>
			<td style="text-align:right; height:1.2cm;" colspan="3" valign="bottom">
				<h3>Student&nbsp;&nbsp;</h3>
				<p>Identity Card&nbsp;&nbsp;</p>
			</td>
		</tr>
	</tbody>
</table>

