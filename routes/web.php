<?php

Route::middleware('IsAdmin')->group(function () {
    //
});

Route::get('/login', 'Auth\LaAdminLogin@page')->name('login');
Route::post('/login', 'Auth\LaAdminLogin@authenticate')->name('authenticate');
