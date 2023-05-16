<?php

Route::group('/migrate', function () {
	Route::get('/', 'Migrate@list');
	Route::get('/all', 'Migrate@all');
	Route::post('/specific-migration', 'Migrate@specificMigration');
});
