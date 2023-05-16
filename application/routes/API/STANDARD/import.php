<?php

// IMPORT
Route::group('import', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::POST('/batch-preview', 'Import@batchStudentPreview');
});
