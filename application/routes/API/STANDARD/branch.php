<?php

// BRANCH
Route::group('branch', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::get('/branch-select', 'Branch@getBranchSelect');
	Route::get('/branch-list-pages', 'Branch@listBranchManagement');
	Route::get('/show/{num:id}', 'Branch@getBranchByID');
	Route::post('/list-branch', 'Branch@getListBranch');
	Route::post('/save', 'Branch@save');
	Route::delete('/delete/{num:id}', 'Branch@delete');
});
