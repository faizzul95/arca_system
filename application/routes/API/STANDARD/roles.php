<?php

// ROLES
Route::group('roles', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::get('/roles-pages', 'Roles@listRolesRbac');
	Route::get('/update/{num:id}', 'Roles@getRolesByID');
	Route::post('/list-roles', 'Roles@getListRoles');
	Route::post('/save', 'Roles@save');
	Route::get('/role-select/{scope?}', 'Roles@getRolesSelect');
	Route::delete('/delete/{num:id}', 'Roles@delete');
});
