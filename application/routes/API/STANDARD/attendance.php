<?php

// ATTENDANCE
Route::group('attendance', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::get('/attendance-student-category/{category}', 'Attendance@getListAttendanceByStudentID');
	Route::post('/list-attendance-slot', 'Attendance@getListDtAttendanceBySlotID');
	Route::post('/record', 'Attendance@recordAttendance');
	Route::post('/access-code-organizer', 'Attendance@organizerAttendanceAccessCode');
	Route::post('/dynamic-qrcode-generate', 'Attendance@generateSessiontQr');
	Route::post('/session-update', 'Attendance@updateSessionQR');
});
