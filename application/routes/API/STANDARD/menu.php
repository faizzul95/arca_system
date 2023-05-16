<?php

// MENU
Route::group('menu', ['middleware' => ['Superadmin', 'Api']], function () {
	Route::get('/menu-pages', 'Menu@listMenuRbac');
	Route::get('/update/{num:id}', 'Menu@getMenuByID');
	Route::post('/list-menu', 'Menu@getListMenu');
	Route::post('/save', 'Menu@save');
	Route::delete('/delete/{num:id}', 'Menu@delete');
	Route::post('/menu-select', 'Menu@getListMenuSelect');
	Route::post('/menu-order-select', 'Menu@getMenuOrderSelect');

	// permission 
	Route::get('/list-menu-div/{num:roleid}', 'Menu@getListMenuPermission');
	Route::get('/list-submenu-div/{num:menuid}/{num:roleid}', 'Menu@getListSubMenuPermission');
	Route::post('/permission', 'Menu@permissionSave');
});
