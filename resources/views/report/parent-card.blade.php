<table style="width:8.6cm;  border-spacing: 0px;">
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
			<td style=" width: 4.3cm; height:2.3cm; text-align:center;"  valign="top">
				<img width="1.7cm" height="2.3cm" src="{{ $studentPicUrl }}" >
			</td>
			<td style=" width: 4.3cm; text-align:center;" valign="top">
				<img width="1.7cm" height="2.3cm" src="{{ $parentPicUrl }}" >
			</td>
		</tr>
		<tr>
			<td style=" width: 4.3cm; height:0.7cm; text-align:center;"  >
				
				<p>{{$student->SA_TITLE_NAME_TH}} {{$student->SA_FIRST_NAME_TH}} {{$student->SA_LAST_NAME_TH}} </p>
			</td>
			<td style=" width: 4.3cm; text-align:center;">
					<p>{{$parent->SP_TITLE_NAME}} {{$parent->SP_FIRST_NAME}} {{$parent->SP_LAST_NAME}}</p>
			</td>
		</tr>
		<tr>
			<td style="width: 4.3cm; height:1.2cm;text-align:center;" valign="top"><h3>น้อง{{$student->SA_NICK_NAME_TH}}</h3></td>
			<td style="width: 4.3cm; text-align:right;" valign="bottom">
				<h3>Parent Card&nbsp;&nbsp;</h3>
			</td>
		</tr>
	</tbody>
</table>
