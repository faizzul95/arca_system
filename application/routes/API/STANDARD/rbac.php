<?php

// RBAC
Route::group('rbac', ['middleware' => ['Superadmin', 'Api']], function () {
	Route::get('/developer-section', 'Rbac@listDeveloperSection');

	// Audit Trail
	Route::get('/audit-tab', 'Rbac@listAudit');
	Route::post('/list-audit', 'Rbac@getListAuditDt');
	Route::delete('/delete-audit/{num:id}', 'Rbac@deleteAuditTrails');
	Route::get('/view-audit/{num:id}', 'Rbac@getAuditTrailByID');

	// Error Log
	Route::get('/error-tab', 'Rbac@listErrorLogs');
	Route::post('/list-error', 'Rbac@getListErrorLogDt');
	Route::delete('/delete-error/{num:id}', 'Rbac@deleteErrorLogs');
	Route::delete('/truncate-error-log/{id?}', 'Rbac@truncateErrorLogs');
	Route::post('/clear-error-filter', 'Rbac@clearErrorByFilter');

	// Database
	Route::get('/database-tab', 'Rbac@listDatabase');
	Route::get('/backup-db', 'Rbac@backupDB');
	Route::post('/list-backup-db', 'Rbac@getListDbBackupDt');
	Route::delete('/delete-backup/{num:id}', 'Rbac@deleteBackup');
	Route::get('/mail/{num:id}', 'Rbac@getBackupDbByID');
	Route::post('/sent-mail-backup', 'Rbac@sentMailDB');

	// Editor
	Route::get('/editor-tab', 'Rbac@editor');
	Route::post('/save-editor', 'Rbac@saveEditor');

	// Menu Abilities
	Route::post('/list-abilities', 'Rbac@getListAbilities');
	Route::post('/abilities-save', 'Rbac@abilitiesSave');
	Route::get('/abilities/{num:id}', 'Rbac@getAbilitiesByID');
	Route::get('/abilities-menu/{num:menuid}/{num:roleid}', 'Rbac@getAbilitiesByMenuID');
	Route::delete('/delete-abilities/{num:id}', 'Rbac@abilitiesDelete');
	Route::post('/abilities-assign', 'Rbac@abilitiesAssign');

	// Email Template
	Route::get('/email-section', 'Rbac@listEmailSection');
	Route::get('/email-template-tab', 'Rbac@listEmailTemplatePage');
	Route::post('/list-email-template', 'Rbac@getListEmailTemplateDt');
	Route::post('/template-email-save', 'Rbac@emailTemplateSave');
	Route::get('/show-template/{num:id}', 'Rbac@getEmailTemplateByID');

	// Email Job Queue
	Route::get('/email-queue-tab', 'Rbac@listEmailQueuePage');
	Route::get('/show-queue-email/{num:id}', 'Rbac@getQueueEmailByID');
	Route::get('/show-preview-email/{num:id}', 'Rbac@getPreviewQueueEmailByID');
	Route::post('/list-email-queue', 'Rbac@getListEmailQueueDt');
	Route::delete('/delete-queue-email/{num:id}', 'Rbac@queueEmailDelete');

	// Others
	Route::get('/remove-cache', 'Rbac@removeCacheFile');
});
