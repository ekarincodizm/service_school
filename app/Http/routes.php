<?php

Route::group(['middleware' => ['cors']], function(){
	
	Route::controller ( 'user', 'UserController' );
	Route::controller ( 'subject', 'SubjectController' );
	Route::controller ( 'student', 'StudentController' );
			
});
