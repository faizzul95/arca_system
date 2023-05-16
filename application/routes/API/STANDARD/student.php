<?php

// STUDENT
Route::group('student', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::post('/list-enrollment', 'Student@getListDtEnrollment');
	Route::post('/list-directory', 'Student@getListDtDirectory');
	Route::post('/list-copy', 'Student@getListDtCopy');
	Route::get('/show-user/{num:id}', 'Student@getStudByUserID');
	Route::get('/show/{num:id}', 'Student@getStudByStudID');
	Route::post('/save', 'Student@save');
	Route::post('/save-copy', 'Student@saveCopy');
	Route::delete('/delete/{num:id}', 'Student@delete');

	Route::post('/qr-lock-device', 'Student@qrLockDeviceID');
	Route::post('/check-device-id', 'Student@qrCheckDeviceID');
});
