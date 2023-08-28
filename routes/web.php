<?php

Route::middleware('IsAdmin')->group(function () {
    Route::prefix('/profile/user')->group(function () {
        Route::post('get', 'LaAdminProfile@getUser')->name('get-user');
        Route::post('save', 'LaAdminProfile@saveProfile')->name('save-profile');
        Route::post('verify', 'LaAdminProfile@verifyUser')->name('verify-user');
        Route::post('change_password', 'LaAdminProfile@changePassword')->name('change-password');
        Route::post('logout', 'Auth\LaAdminLogin@logout')->name('logout');
    });

    Route::prefix('/users')->group(function () {
        Route::post('/get', 'LaAdminUsers@getUsers')->name('get-users');
        Route::post('/save', 'LaAdminUsers@saveUser')->name('save-user');
        Route::post('/delete', 'LaAdminUsers@deleteUser')->name('delete-user');

        Route::post('/new', 'LaAdminUsers@newUser')->name('new-user');

        Route::post('/forget-password', 'LaAdminUsers@sendPasswordResetNotification')->name('forget-password');
    });

    Route::prefix('/roles')->group(function () {
        Route::post('/get', 'LaAdminRoles@getRoles')->name('get-roles');
    });

    Route::prefix('/pages')->group(function () {
        Route::post('/get', 'LaAdminPage@getPages')->name('get-pages');
        Route::post('/get/page', 'LaAdminPage@getPage')->name('get-page');
        Route::post('/get/data', 'LaAdminPage@getData')->name('get-page-data');
        Route::post('/file/download', 'LaAdminPage@downloadFile')->name('download-file');
        Route::post('/file/delete', 'LaAdminPage@deleteFile')->name('delete-file');
        Route::post('/save', 'LaAdminPage@saveData')->name('save-page-data');
        Route::post('/add', 'LaAdminPage@addPost')->name('add-page-post');
        Route::post('/delete', 'LaAdminPage@deletePost')->name('delete-page-post');
    });

    Route::post('/errors/get-all', 'LaAdminErrorReport@getErrors')->name('get-errors');
    Route::post('/errors/get', 'LaAdminErrorReport@getReport')->name('get-report');
    Route::post('/error/read', 'LaAdminErrorReport@read')->name('error-read');
    Route::post('/error/fixed', 'LaAdminErrorReport@fixed')->name('error-fixed');
    Route::post('/error/event', 'LaAdminErrorReport@event')->name('error-event');
});

Route::get('/login', 'Auth\LaAdminLogin@page')->name('login');
Route::post('/login', 'Auth\LaAdminLogin@authenticate')->name('authenticate');

Route::get('/register', 'Auth\LaAdminRegister@page')->name('register');
Route::post('/register', 'Auth\LaAdminRegister@register');
