<?php

Route::group('/sysadmin', function () {
	Route::get('/', 'Sysadmin@login');
	Route::get('/login', 'Sysadmin@login');
	Route::post('/sign-in', 'Sysadmin@authorize');
	Route::post('/socialite', 'Sysadmin@socialite');
	Route::post('/verify-user', 'Sysadmin@Verify2FA');
});
