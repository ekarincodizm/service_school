<?php

Route::group(['middleware' => ['cors']], function(){
	
	Route::controller ( 'user', 'UserController' );
	Route::controller ( 'subject', 'SubjectController' );
	Route::controller ( 'student', 'StudentController' );
	Route::controller ( 'parent', 'ParentController' );
	Route::controller ( 'room', 'RoomController' );
	Route::controller ( 'class-room', 'ClassRoomController' );
	Route::controller ( 'address', 'AddressController' );
	Route::controller ( 'register-class', 'RegisterClassController' );
	Route::controller ( 'confirm-payment', 'ConfirmPaymentController' );
	Route::controller ( 'report', 'ReportController' );
	Route::controller ( 'image', 'ImageController' );
	Route::controller ( 'stat', 'StatController' );
	
});
