<table style="width: 100%; border: 1px solid;">
	<tbody>
		<tr>
			<td style="width: 80%;"></td>
			<td style="width: 20%; text-align:center;">
				<h3>ใบแจ้งชำระเงิน</h3>
				<p>สำหรับลูกค้า</p>
				<p>&nbsp;</p>
			</td>
		</tr>
		<tr>
			<td>ค่าธรรมเนียม 10 บาทใน กทม. และปริมณฑล / 25 บาทในต่างจังหวัด</td>
			<td style="text-align:center;">
			<p>(เลขที่บิล : {{$bill->BILL_NO}})</p>
			</td>
		</tr>
	</tbody>
</table>

<table style="width: 100%; border: 1px solid; border-top-style: none; border-spacing: 0px;">
	<tbody>
		<tr>
			<td style="width: 10%; height:100px; text-align:center; border-right: 1px solid; border-bottom: 1px solid;"><img style="width:130px;" src="{{ URL::asset('assets/images/logo-school-short.jpg')}}"></td>
			<td style="width: 32%;" valign="top" rowspan="2">
				<h3 style="text-decoration: underline;">โรงเรียนอนุบาลเปล่งประสิทธิ์ศรีนครินทร์</h3>
				<p>	ที่อยู่ : 12/9 ซอยเสรีวิล่า ถนนศรีนครินทร์<br/>
					แขวงหนองบอน เขตประเวศ กรุงเทพฯ 10250<br/>
					Tax ID : 0994000006951
				</p>
			</td>
			<td style="width: 58%;" valign="top" rowspan="2">
				<p>วันที่ / Date : {{App\Http\Controllers\UtilController\DateUtil::convertDateStringToTextThai(App\Http\Controllers\UtilController\DateUtil::getDisplaytoStore($bill->CREATE_DATE))}}</p>
				<table style="width: 101%; border-spacing: 0px;">
					<tr>
						<td colspan="2" style="width: 500px; border: 1px solid; border-right-style: none;" valign="top">
							<h3>SERVICE CODE : PLENGSRI</h3>
						</td>
						<td></td>
					</tr>
					<tr>
						<td style="width: 200px; border-left: 1px solid;" valign="top">
							<p>ชื่อ-สกุล</p>
						</td>
						<td style="width: 300px; border-bottom: 1px solid;" valign="top">{{$studentAccount->SA_TITLE_NAME_TH.$studentAccount->SA_FIRST_NAME_TH.'&nbsp;&nbsp;&nbsp;'.$studentAccount->SA_LAST_NAME_TH}}</td>
						<td></td>
					</tr>
					<tr>
						<td valign="top" style="border-left: 1px solid;">
							<p>รหัสประจำตัว Customer No. (Ref.1)</p>
						</td>
						<td style="border-bottom: 1px solid;" valign="top">{{$studentAccount->SA_STUDENT_ID}}</td>
						<td></td>
					</tr>
					@if($bill->BILL_REGISTER_STATUS == 'N' || $bill->BILL_REGISTER_STATUS == 'C')
					<tr>
						<td valign="top" style="border-left: 1px solid;">
							<p>ปีการศึกษาเทอม (Ref.2)</p>
						</td>
						<td style="border-bottom: 1px solid;" valign="top">
							<p>
								{{$bill->BILL_YEAR}}0{{$bill->BILL_TERM}}
							</p>
						</td>
						<td></td>
					</tr>
					@elseif($bill->BILL_REGISTER_STATUS == 'SM')
					<tr>
						<td valign="top" style="border-left: 1px solid;">
							<p>ปีการศึกษาซัมเมอร์ (Ref.2)</p>
						</td>
						<td style="border-bottom: 1px solid;" valign="top">
							<p>
								{{$bill->BILL_YEAR}}
								@foreach ($billDetails as $index =>$billDetail)
									@if($billDetail->BD_TERM_FLAG == 'Y')
										{{$billDetail->subject->SUBJECT_CODE}}
									@endif
								@endforeach
							</p>
						</td>
						<td></td>
					</tr>
					@else
					<tr>
						<td valign="top" style="border-left: 1px solid;">
							<p>รหัสกิจกรรมที่เลือกเรียน (Ref.2)</p>
						</td>
						<td style="border-bottom: 1px solid;" valign="top">
							<p>
								@foreach ($billDetails as $index =>$billDetail)
									@if(isset($billDetail->subject))
										@if(isset($billDetail->subject->SUBJECT_CODE))
											{{$billDetail->subject->SUBJECT_CODE}}&nbsp;
										@endif		
									@endif
								@endforeach
							</p>
						</td>
						<td></td>
					</tr>
					@endif
					<tr>
						<td valign="bottom" colspan="2" style="text-align:right; border-left: 1px solid; border-bottom: 1px solid;">
							<p>&nbsp;</p>
							<p>Tel. 02-743-4047&nbsp;</p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" valign="bottom" style="text-align:center;">
				<h3>เพื่อเข้าบัญชี โรงเรียนอนุบาลเปล่งประสิทธิ์ศรีนครินทร์</h3>
			</td>
		</tr>
		<tr>
			<td colspan="2" valign="top">
			<table style="width: 100%; border-spacing: 0px;">
				<tbody>
					<tr>
						<td valign="top" style="text-align:right; width: 27%;">
							<img style="width:25px;" src="{{ URL::asset('assets/images/BBL-logo.jpg')}}">
						</td>
						<td>
							<p>&nbsp;&nbsp;บมจ. ธนาคารกรุงเทพ (Br.no.1090) (10/25)</p>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
		<tr>
			<td colspan="3" valign="bottom" style="text-align:center;">
				<h3 style="color:red;">**รับเฉพาะเงินสดเท่านั้น**</h3>
			</td>
		</tr>

	</tbody>
</table>

<table style="width: 100%; border: 1px solid; border-top-style: none; border-spacing: 0px;">
	<tbody>

		<tr>
			<td style="width: 20%; border-right: 1px solid; " colspan="2" ></td>
			<td style="width: 60%; border-right: 1px solid; "></td>
			<td style="width: 17%;"></td>
			<td style="width: 3%;"></td>
		</tr>

		<tr>
			<td valign="middle" style="text-align:center; border-right: 1px solid; border-bottom: 1px solid;" colspan="2">
				<p>รายการ</p>
			</td>
			<td valign="middle" style="text-align:center; border-right: 1px solid; border-bottom: 1px solid;">
				<p>จำนวนเงินที่เป็นตัวอักษร</p>
			</td>
			<td colspan="2" valign="middle" style="text-align:center; border-bottom: 1px solid;">
				<p>จำนวนเงิน (บาท)</p>
			</td>
		</tr>

		<tr>
			<td valign="middle" style="text-align:right;  height:50px;">
				<img style="width:20px;" src="{{ URL::asset('assets/images/square.jpg')}}">
			</td>
			<td valign="middle" style="text-align:left;  height:50px; border-right: 1px solid;">
				<h3>&nbsp;เงินเสด<h3>
			</td>
			<td valign="middle" style="text-align:center; border-right: 1px solid;">
				<h3>({{App\Http\Controllers\UtilController\StringUtil::convertNumberToText($bill->BILL_TOTAL_PRICE)}})</h3>
			</td>
			<td valign="middle" style="text-align:right;  border-right: 1px solid;">
				<h3>{{number_format($bill->BILL_TOTAL_PRICE)}}&nbsp;</h3>
			</td>
			<td valign="middle" style="text-align:center;">
				<h3>-</h3>
			</td>
		</tr>

		<tr>
			<td valign="middle" colspan="5" style="text-align:center; border-top: 1px solid;">
				<p>เพื่อความสะดวกของท่าน กรุณานำใบแจ้งการชำระเงินพร้อมใบแจ้งหนี้ไปชำระได้ที่ บมจ. ธนาคารกรุงเทพ ทุกสาขาทั่วประเทศ</p>
			</td>
		</tr>

	</tbody>
</table> 

<table style="width: 100%; border: 1px solid; border-top-style: none; border-bottom-style: none;border-spacing: 0px;">
	<tbody>
		<tr>
			<td valign="top" style="text-align:center; width:15%; height: 230px;">
				<p style="text-decoration: underline;">รายละเอียด</p>
			</td>
			<td valign="top" style="text-align:left;">
				<?php 
					$isPrintSubject = false;
					$isPrintOthers = false;
				?>
				@foreach ($billDetails as $index =>$billDetail)
					@if(isset($billDetail->subject))
						@if(isset($billDetail->subject->SUBJECT_CODE))
							@if(!$isPrintSubject)

								@if($bill->BILL_REGISTER_STATUS == 'SM')
									<p style="text-decoration: underline;">
										ซัมเมอร์ที่เลือก
									</p>
								@else
									<p style="text-decoration: underline;">
										กิจกรรมที่เลือก
									</p>
								@endif
								<?php $isPrintSubject = true; ?>

							@endif
							<p>
								{{$billDetail->subject->SUBJECT_CODE}} {{$billDetail->subject->SUBJECT_NAME}} จำนวน {{number_format($billDetail->BD_PRICE)}} บาท
							</p>
						@endif		
					@endif
				@endforeach
				@foreach ($billDetails as $index =>$billDetail)
					@if(isset($billDetail->subject))
						@if(!isset($billDetail->subject->SUBJECT_CODE))

							@if(!$isPrintOthers)
								<p style="text-decoration: underline;">
									ค่าใช้จ่าย
								</p>
								<?php $isPrintOthers = true; ?>

							@endif

							<p>
								{{$billDetail->subject->SUBJECT_NAME}} จำนวน {{number_format($billDetail->BD_PRICE)}} บาท
							</p>
						@endif		
					@else
						@if(!$isPrintOthers)
							<p style="text-decoration: underline;">
								ค่าใช้จ่าย
							</p>
							<?php $isPrintOthers = true; ?>

						@endif
						<p>
							{{$billDetail->BD_REMARK}} จำนวน {{number_format($billDetail->BD_PRICE)}} บาท
						</p>
					@endif
				@endforeach
			</td>
		</tr>
	</tbody>
</table>

<table style="width: 100%; border: 1px solid; border-top-style: none; border-bottom-style: dotted;border-spacing: 0px;">
	<tbody>
		<tr>
			<td valign="top" style="text-align:ledt;">
				<p><font size="2">กรุณาตัดตามรอยปรุ</font></p>
			</td>
		</tr>
	</tbody>
</table>

<!-- ====================================================== -->
<table style="border: none; "><tbody><tr><td style="height:30px;"></td></tr></tbody></table>
<!-- ====================================================== -->

<table style="width: 100%; border: 1px solid;">
	<tbody>
		<tr>
			<td style="width: 80%;"></td>
			<td style="width: 20%; text-align:center;">
				<h3>ใบแจ้งชำระเงิน</h3>
				<p>สำหรับธนาคาร</p>
				<p>&nbsp;</p>
			</td>
		</tr>
		<tr>
			<td>ค่าธรรมเนียม 10 บาทใน กทม. และปริมณฑล / 25 บาทในต่างจังหวัด</td>
			<td style="text-align:center;">
			<p>(เลขที่บิล : {{$bill->BILL_NO}})</p>
			</td>
		</tr>
	</tbody>
</table>
<table style="width: 100%; border: 1px solid; border-top-style: none; border-spacing: 0px;">
	<tbody>
		<tr>
			<td style="width: 10%; height:100px; text-align:center; border-right: 1px solid; border-bottom: 1px solid;"><img style="width:130px;" src="{{ URL::asset('assets/images/logo-school-short.jpg')}}"></td>
			<td style="width: 32%;" valign="top" rowspan="2">
				<h3 style="text-decoration: underline;">โรงเรียนอนุบาลเปล่งประสิทธิ์ศรีนครินทร์</h3>
				<p>	ที่อยู่ : 12/9 ซอยเสรีวิล่า ถนนศรีนครินทร์<br/>
					แขวงหนองบอน เขตประเวศ กรุงเทพฯ 10250<br/>
					Tax ID : 0994000006951
				</p>
			</td>
			<td style="width: 58%;" valign="top" rowspan="2">
				<p>วันที่ / Date : {{App\Http\Controllers\UtilController\DateUtil::convertDateStringToTextThai(App\Http\Controllers\UtilController\DateUtil::getDisplaytoStore($bill->CREATE_DATE))}}</p>
				<table style="width: 101%; border-spacing: 0px;">
					<tr>
						<td colspan="2" style="width: 500px; border: 1px solid; border-right-style: none;" valign="top">
							<h3>SERVICE CODE : PLENGSRI</h3>
						</td>
						<td></td>
					</tr>
					<tr>
						<td style="width: 200px; border-left: 1px solid;" valign="top">
							<p>ชื่อ-สกุล</p>
						</td>
						<td style="width: 300px; border-bottom: 1px solid;" valign="top">{{$studentAccount->SA_TITLE_NAME_TH.$studentAccount->SA_FIRST_NAME_TH.'&nbsp;&nbsp;&nbsp;'.$studentAccount->SA_LAST_NAME_TH}}</td>
						<td></td>
					</tr>
					<tr>
						<td valign="top" style="border-left: 1px solid;">
							<p>รหัสประจำตัว Customer No. (Ref.1)</p>
						</td>
						<td style="border-bottom: 1px solid;" valign="top">{{$studentAccount->SA_STUDENT_ID}}</td>
						<td></td>
					</tr>
					@if($bill->BILL_REGISTER_STATUS == 'N' || $bill->BILL_REGISTER_STATUS == 'C')
					<tr>
						<td valign="top" style="border-left: 1px solid;">
							<p>ปีการศึกษาเทอม (Ref.2)</p>
						</td>
						<td style="border-bottom: 1px solid;" valign="top">
							<p>
								{{$bill->BILL_YEAR}}0{{$bill->BILL_TERM}}
							</p>
						</td>
						<td></td>
					</tr>
					@elseif($bill->BILL_REGISTER_STATUS == 'SM')
					<tr>
						<td valign="top" style="border-left: 1px solid;">
							<p>ปีการศึกษาซัมเมอร์ (Ref.2)</p>
						</td>
						<td style="border-bottom: 1px solid;" valign="top">
							<p>
								{{$bill->BILL_YEAR}}
								@foreach ($billDetails as $index =>$billDetail)
									@if($billDetail->BD_TERM_FLAG == 'Y')
										{{$billDetail->subject->SUBJECT_CODE}}
									@endif
								@endforeach
							</p>
						</td>
						<td></td>
					</tr>
					@else
					<tr>
						<td valign="top" style="border-left: 1px solid;">
							<p>รหัสกิจกรรมที่เลือกเรียน (Ref.2)</p>
						</td>
						<td style="border-bottom: 1px solid;" valign="top">
							<p>
								@foreach ($billDetails as $index =>$billDetail)
									@if(isset($billDetail->subject))
										@if(isset($billDetail->subject->SUBJECT_CODE))
											{{$billDetail->subject->SUBJECT_CODE}}&nbsp;
										@endif		
									@endif
								@endforeach
							</p>
						</td>
						<td></td>
					</tr>
					@endif
					<tr>
						<td valign="bottom" colspan="2" style="text-align:right; border-left: 1px solid; border-bottom: 1px solid;">
							<p>&nbsp;</p>
							<p>Tel. 02-743-4047&nbsp;</p>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" valign="bottom" style="text-align:center;">
				<h3>เพื่อเข้าบัญชี โรงเรียนอนุบาลเปล่งประสิทธิ์ศรีนครินทร์</h3>
			</td>
		</tr>
		<tr>
			<td colspan="2" valign="top">
			<table style="width: 100%; border-spacing: 0px;">
				<tbody>
					<tr>
						<td valign="top" style="text-align:right; width: 27%;">
							<img style="width:25px;" src="{{ URL::asset('assets/images/BBL-logo.jpg')}}">
						</td>
						<td>
							<p>&nbsp;&nbsp;บมจ. ธนาคารกรุงเทพ (Br.no.1090) (10/25)</p>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
		<tr>
			<td colspan="3" valign="bottom" style="text-align:center;">
				<h3 style="color:red;">**รับเฉพาะเงินสดเท่านั้น**</h3>
			</td>
		</tr>

	</tbody>
</table>

<table style="width: 100%; border: 1px solid; border-top-style: none; border-spacing: 0px;">
	<tbody>

		<tr>
			<td style="width: 20%; border-right: 1px solid; " colspan="2" ></td>
			<td style="width: 60%; border-right: 1px solid; "></td>
			<td style="width: 17%;"></td>
			<td style="width: 3%;"></td>
		</tr>

		<tr>
			<td valign="middle" style="text-align:center; border-right: 1px solid; border-bottom: 1px solid;" colspan="2">
				<p>รายการ</p>
			</td>
			<td valign="middle" style="text-align:center; border-right: 1px solid; border-bottom: 1px solid;">
				<p>จำนวนเงินที่เป็นตัวอักษร</p>
			</td>
			<td colspan="2" valign="middle" style="text-align:center; border-bottom: 1px solid;">
				<p>จำนวนเงิน (บาท)</p>
			</td>
		</tr>

		<tr>
			<td valign="middle" style="text-align:right;  height:50px;">
				<img style="width:20px;" src="{{ URL::asset('assets/images/square.jpg')}}">
			</td>
			<td valign="middle" style="text-align:left;  height:50px; border-right: 1px solid;">
				<h3>&nbsp;เงินเสด<h3>
			</td>
			<td valign="middle" style="text-align:center; border-right: 1px solid;">
				<h3>({{App\Http\Controllers\UtilController\StringUtil::convertNumberToText($bill->BILL_TOTAL_PRICE)}})</h3>
			</td>
			<td valign="middle" style="text-align:right;  border-right: 1px solid;">
				<h3>{{number_format($bill->BILL_TOTAL_PRICE)}}&nbsp;</h3>
			</td>
			<td valign="middle" style="text-align:center;">
				<h3>-</h3>
			</td>
		</tr>

		<tr>
			<td valign="middle" colspan="5" style="text-align:center; border-top: 1px solid;">
				<p>เพื่อความสะดวกของท่าน กรุณานำใบแจ้งการชำระเงินพร้อมใบแจ้งหนี้ไปชำระได้ที่ บมจ. ธนาคารกรุงเทพ ทุกสาขาทั่วประเทศ</p>
			</td>
		</tr>

	</tbody>
</table>