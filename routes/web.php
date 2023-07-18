<?php

Route::middleware('IsAdmin')->group(function () {
    Route::post('/profile/user/get', 'Auth\LaAdminLogin@getUser')->name('get-user');
    Route::post('/profile/user/save', 'Auth\LaAdminLogin@saveProfile')->name('save-profile');
    Route::post('/profile/user/verify', 'Auth\LaAdminLogin@verifyUser')->name('verify-user');
    Route::post('/profile/user/change_password', 'Auth\LaAdminLogin@changePassword')->name('change-password');
    Route::post('/profile/user/logout', 'Auth\LaAdminLogin@logout')->name('logout');

    Route::post('/errors/get-all', 'LaAdminErrorReport@getErrors')->name('get-errors');
    Route::post('/errors/get', 'LaAdminErrorReport@getReport')->name('get-report');
    Route::post('/error/read', 'LaAdminErrorReport@read')->name('error-read');
    Route::post('/error/fixed', 'LaAdminErrorReport@fixed')->name('error-fixed');
    Route::post('/error/event', 'LaAdminErrorReport@event')->name('error-event');
});

Route::get('/login', 'Auth\LaAdminLogin@page')->name('login');
Route::post('/login', 'Auth\LaAdminLogin@authenticate')->name('authenticate');
