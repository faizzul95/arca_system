<?php

// MANAGEMENT
Route::group('management', ['middleware' => ['Sanctum', 'Api']], function () {
	Route::get('/education-select', 'Management@getEducationSelect');
	Route::get('/faculty-select', 'Management@getFacultySelect');
	Route::get('/program-select/{id?}/{branchid?}', 'Management@getProgramSelect');

	// tab pages
	Route::get('/faculty-list-pages', 'Management@listFacultyManagement');
	Route::get('/college-level-list-pages', 'Management@listLevelCollegeManagement');
	Route::get('/program-list-pages', 'Management@listProgramManagement');
	Route::get('/education-list-pages', 'Management@listEducationManagement');

	// education
	Route::post('/list-education', 'Management@getListDtEducation');
	Route::get('/show-education/{num:id}', 'Management@getEducationByID');
	Route::post('/save-education', 'Management@saveEducation');
	Route::delete('/delete-education/{num:id}', 'Management@deleteEducation');

	// faculty
	Route::post('/list-faculty', 'Management@getListDtFaculty');
	Route::get('/show-faculty/{id}', 'Management@getFacultyByID');
	Route::post('/save-faculty', 'Management@saveFaculty');
	Route::delete('/delete-faculty/{num:id}', 'Management@deleteFaculty');

	// program
	Route::post('/list-program', 'Management@getListDtProgram');
	Route::get('/show-program/{num:id}', 'Management@getProgramByID');
	Route::post('/save-program', 'Management@saveProgram');
	Route::delete('/delete-program/{num:id}', 'Management@deleteProgram');

	// college level
	Route::get('/college-level-select', 'Management@getCollegeLevelSelect');
	Route::post('/list-college-level', 'Management@getListDtCollegeLevel');
	Route::get('/show-college-level/{num:id}', 'Management@getCollegeLevelByID');
	Route::post('/save-college-level', 'Management@saveCollegeLevel');
	Route::delete('/delete-college-level/{num:id}', 'Management@deleteCollegeLevel');
});
