<?php

/**
 * Welcome to Luthier-CI!
 *
 * This is your main route file. Put all your HTTP-Based routes here using the static
 * Route class methods
 *
 * Examples:
 *
 *    Route::get('foo', 'bar@baz');
 *      -> $route['foo']['GET'] = 'bar/baz';
 *
 *    Route::post('bar', 'baz@fobie', [ 'namespace' => 'cats' ]);
 *      -> $route['bar']['POST'] = 'cats/baz/foobie';
 *
 *    Route::get('blog/{slug}', 'blog@post');
 *      -> $route['blog/(:any)'] = 'blog/post/$1'
 */

// Route::get('/', function () {
// 	luthier_info();
// })->name('homepage');

// GENERAL
Route::set('default_controller', 'auth');
Route::get('/', 'Auth@index');

require __DIR__ . '/PAGES/Auth.php';
require __DIR__ . '/PAGES/Error.php';
require __DIR__ . '/PAGES/SysAdmin.php';
require __DIR__ . '/PAGES/Migration.php';

Route::get('/dashboard', 'dashboard@index', ['middleware' => 'Sanctum']);
Route::get('/user', 'user@index', ['middleware' => 'Sanctum']);
Route::get('/student', 'student@index', ['middleware' => 'Sanctum']);
Route::get('/profile', 'Profile@index', ['middleware' => 'Sanctum']);
Route::get('/academic', 'Academic@index', ['middleware' => 'Sanctum']);

Route::group('/event', ['middleware' => ['Sanctum']], function () {
	Route::get('/', 'Event@index');
	Route::get('/qr-student-scanner', 'Event@qrCodeScanner');
	Route::get('/list', 'Event@list');
});

Route::group('/college', ['middleware' => ['Sanctum']], function () {
	Route::get('/configuration', 'College@configuration');
	Route::get('/application', 'College@application');
});

Route::get('/management', 'Management@index', ['middleware' => 'Sanctum']);
Route::get('/rbac', 'Rbac@index', ['middleware' => 'Superadmin']);

Route::set('404_override', function () {
	return errorpage('404');
});

Route::set('translate_uri_dashes', FALSE);
