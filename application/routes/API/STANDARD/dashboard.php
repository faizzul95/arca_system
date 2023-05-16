<?php

// DASHBOARD
Route::group('dashboard', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::get('/admin-data', 'Dashboard@getAdminDashboardData');
	Route::get('/srk-data', 'Dashboard@getSrkDashboardData');
	Route::get('/organizer-data', 'Dashboard@getOrganizerDashboardData');
	Route::get('/student-event-list', 'Dashboard@getEventDashboard');
	Route::get('/calendar/{date?}', 'Dashboard@getEventCalendarByDate');
	Route::get('/sticker-count', 'Dashboard@getStudentStickerCount');
});
