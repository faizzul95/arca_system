<?php

// ACADEMIC
Route::group('academic', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::post('/list-academic', 'Academic@getListDtAcademicYear');
	Route::get('/show/{id?}', 'Academic@getAcademicByID');
	Route::delete('/delete/{num:id}', 'Academic@delete');
	Route::post('/save', 'Academic@save');
	Route::post('/switch-academic', 'Academic@switchDefaultAcademic');
	Route::post('/academic-order-list', 'Academic@getListAcademicOrderSelect');
	Route::post('/save-config-sticker', 'ConfigStickerCollege@save');
	Route::post('/previous-academic-data', 'Academic@getPreviousAcademicByOrderNo');
	Route::get('/academic-event-select', 'Academic@getAcademicEventSelect');
});
