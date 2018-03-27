<div 
	style="	width:5.4cm; 
			height:8.6cm; 
			background-image: url({{URL::asset('assets/images/student-card-layout.jpg')}});
			background-position: center center;
    		background-repeat: no-repeat;
			background-size: 100% 100%;">
	
	<div style="height:1.98cm;"></div>
			   
	<table style="border-spacing: 0px; width:98.5%;">
		<tbody>
			<tr>
				<td style="width:100%; text-align:center;" valign="middle"><img width="2.9cm" height="3.975cm" src="{{ $studentPicUrl }}"></td>
			</tr>
		</tbody>
	</table>
	
	<div style="height:0.1cm;"></div>

	<table style="border-spacing: 0px; width:98.5%;">
		<tbody>
			<tr>
				<td style="text-align:center;" valign="middle">
					<span style="font-size:20px;">{{$student->SA_TITLE_NAME_TH}} {{$student->SA_FIRST_NAME_TH}} {{$student->SA_LAST_NAME_TH}}</span><br/>
					<span style="font-size:22px;"><b>น้อง{{$student->SA_NICK_NAME_TH}}</b></span>
				</td>
			</tr>
		</tbody>
	</table>


<div>