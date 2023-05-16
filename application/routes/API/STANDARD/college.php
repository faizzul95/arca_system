<?php

// COLLEGE
Route::group('college', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::post('/list-college', 'College@getListDtCollege');
	Route::post('/save-college', 'College@saveCollege');
	Route::get('/show/{num:id?}', 'College@getCollegeByID');
	Route::get('/college-list-div/{num:collegeid?}', 'College@getListCollegeDiv');
	Route::get('/college-room-select/{num:collegeid?}', 'College@getCollegeRoomSelectByCollegeID');
	Route::get('/college-select/{branchid?}/{filter?}', 'College@getCollegeSelect');
	Route::delete('/delete-college/{num:id?}', 'College@deleteCollege');

	Route::post('/list-room', 'College@getListDtCollegeRoom');
	Route::post('/save-room', 'College@saveCollegeRoom');
	Route::get('/show-room/{num:id?}', 'College@getCollegeRoomByID');
	Route::delete('/delete-room/{num:id?}', 'College@deleteCollegeRoom');
});
