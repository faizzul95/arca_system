<?php

Route::group('/error', function () {
	Route::get('/403', 'Errorpage@err403');
	Route::get('/404', 'Errorpage@err404');
	Route::get('/maintenance', 'Errorpage@maintenance');
});
