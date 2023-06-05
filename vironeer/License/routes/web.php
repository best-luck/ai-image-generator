<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::prefix('install')->name('install.')->middleware('installed')->group(function () {
        Route::get('/', 'InstallController@redirect')->name('index');
        Route::get('requirements', 'InstallController@requirements');
        Route::post('requirements', 'InstallController@requirementsAction')->name('requirements');
        Route::get('permissions', 'InstallController@permissions');
        Route::post('permissions', 'InstallController@permissionsAction')->name('permissions');
        Route::get('license', 'InstallController@license');
        Route::post('license', 'InstallController@licenseAction')->name('license');
        Route::get('database', 'InstallController@databaseDetails');
        Route::post('database', 'InstallController@databaseDetailsAction')->name('database.details');
        Route::get('import', 'InstallController@databaseImport');
        Route::post('import', 'InstallController@databaseImportAction')->name('database.import');
        Route::post('import/download', 'InstallController@databaseImportDownload')->name('database.import.download');
        Route::post('import/skip', 'InstallController@databaseImportSkip')->name('database.import.skip');
        Route::get('complete', 'InstallController@complete');
        Route::post('complete', 'InstallController@completeAction')->name('complete');
        Route::post('complete/back', 'InstallController@completeBack')->name('complete.back');
    });
});
