<?php

// USER
Route::group('user', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::post('/list', 'User@getUserList');
	Route::get('/show/{num:id?}', 'User@getUserByID');
	Route::get('/archive/{num:id?}', 'User@archive');
	Route::get('/view-qr-2fa', 'User@get2FAInfo');
	Route::post('/save', 'User@save');
	Route::post('/reset-username', 'User@resetUsername');
	Route::post('/reset-password', 'User@resetPassword');
	Route::post('/profile-upload', 'User@uploadProfile');
	Route::post('/verify-2fa-status', 'User@changeStatus2FA');
	Route::post('/save-student-bulk', 'User@saveStudentBulk');
	Route::post('/check-matric-code', 'User@checkMatricCodeExist');
	Route::delete('/delete/{num:id}', 'User@delete');
});
