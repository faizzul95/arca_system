<?php

// PROFILE
Route::group('profile', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::get('/list-profile-li-userid', 'Profile@getListLiProfileByUserID');
	Route::post('/list-profile-userid', 'Profile@getListDtProfileByUserID');
	Route::post('/select-profile-userid', 'Profile@getSelectProfileRolesByUserID');
	Route::post('/set-default-profile', 'Profile@setDefaultProfileByUserID');
	Route::post('/list-all-organizer', 'Profile@getAllListDtProfileOrganizer');
	Route::delete('/delete/{num:id}', 'Profile@delete');
	Route::post('/save', 'Profile@save');
});
