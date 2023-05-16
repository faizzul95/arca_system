<?php

// EXPORT
Route::group('export', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::post('/unoffered-print-list', 'Export@printListUnofferedCollege');
	Route::post('/attendance-print-list', 'Export@exportSlotAttendanceBySlotID');
	Route::get('/enrollment-print-list/{num:id}/{num:branch?}/{num:academic?}', 'Export@exportListEnrollmentByCollegeID');
	Route::get('/enrollment-export-list/{num:id}/{num:branch?}/{num:academic?}', 'Export@exportListEnrollmentExcelByCollegeID');
});
