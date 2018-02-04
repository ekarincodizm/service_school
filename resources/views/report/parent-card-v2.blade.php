
<div 
	style="	width:5.4cm; 
			height:8.6cm; 
			background-image: url({{URL::asset('assets/images/parent-card-layout.jpg')}});
			background-position: center center;
    		background-repeat: no-repeat;
			background-size: 100% 100%;">
	
	<div style="height:2.13cm;"></div>
			   
	<table style="border-spacing: 0px; width:98.5%;">
		<tbody>
			<tr>
				<td style="width:100%; text-align:center;" valign="middle"><img width="2cm" height="2.65cm" src="{{ $parentPicUrl }}"></td>
			</tr>
		</tbody>
	</table>
	
	<div style="height:0.35cm;"></div>

	<table style="border-spacing: 0px; width:98.5%;">
		<tbody>
			<tr>
				<td style="text-align:center;" valign="middle">
					<p>{{$parent->SP_TITLE_NAME}} {{$parent->SP_FIRST_NAME}} {{$parent->SP_LAST_NAME}}</p>
				</td>
			</tr>
		</tbody>
	</table>

	<div style="height:0.25cm;"></div>

	<table style="border-spacing: 0px; width:98.5%;">
		<tbody>
			<tr>
				<td style="text-align:center;" valign="middle">
					<p>ผู้ปกครองของ</p>
					<h3>น้อง{{$student->SA_NICK_NAME_TH}}</h3>
					<p>{{$student->SA_TITLE_NAME_TH}} {{$student->SA_FIRST_NAME_TH}} {{$student->SA_LAST_NAME_TH}}</p>
				</td>
			</tr>
		</tbody>
	</table>


<div>


