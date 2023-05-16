<?php

// EVENT
Route::group('event', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::get('/show/{num:id}', 'Event@getEventByID');
	Route::post('/list-event', 'Event@getEventList');
	Route::post('/cancel-event', 'Event@cancelEvent');
	Route::post('/save', 'Event@save');
	Route::get('/list-event-pwa', 'Event@getListEventStudentDashboard');
	Route::delete('/delete/{num:id}', 'Event@delete');
	Route::post('/list-event-organizer', 'Event@getEventOrganizerList');
});

// EVENT SCHEDULE
Route::group('EventSchedule', ['middleware' => ['Sanctum']], function () {
	Route::get('/show/{num:id}', 'EventSchedule@getSlotByID');
	Route::delete('/delete/{num:id}', 'Event@delete');
});

// EVENT ORGANIZER
Route::group('EventOrganizer', ['middleware' => ['Sanctum']], function () {
	Route::delete('/delete/{num:id}', 'Event@delete');
});
