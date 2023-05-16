<?php

Route::group('auth', function () {
	Route::get('/', 'Auth@index');
	Route::get('/logout', 'Auth@logout');
	Route::get('/forgot-password', 'Auth@forgot');
	Route::post('/sign-in', 'Auth@authorize', ['middleware' => ['Api']]);
	Route::post('/socialite', 'Auth@socialite', ['middleware' => ['Api']]);
	Route::post('/sent-email', 'Auth@reset', ['middleware' => ['Api']]);
	Route::post('/verify-user', 'Auth@Verify2FA', ['middleware' => ['Api']]);
	Route::post('/switchProfile', 'Auth@switchProfile', ['middleware' => ['Api']]);
});
