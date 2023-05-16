<?php

// APPLICATIONS
Route::group('applications', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::post('/list-application-admin', 'Applications@getApplicationListDt');
	Route::post('/list-application-srk', 'Applications@getApplicationListSrkDt');
	Route::post('/card-info', 'Applications@getInfoCard');
	Route::post('/checked-status-application', 'Applications@updateCheckStatusApplication');
	Route::get('/show/{num:id}', 'Applications@getApplicationByAppID');

	Route::post('/approval', 'Applications@applicationApproval');
	Route::post('/bulk-approve', 'Applications@bulkApprove');
	Route::post('/bulk-reject', 'Applications@bulkReject');
	Route::post('/bulk-approval-list', 'Applications@getApplicationListSrkBulkDt');
	Route::post('/unoffered-list', 'Applications@getListApplicationUnoffered');
});
