<?php

use Illuminate\Support\Facades\Route;

// Admin login page
Route::get('/admin/login', function () {
    return response()->file(public_path('admin-login.html'));
});

// Admin panel (check session)
Route::get('/admin', function () {
    if (!session('admin_logged_in')) {
        return redirect('/admin/login');
    }
    return response()->file(public_path('admin.html'));
});

// Vote page
Route::get('/vote', function () {
    return response()->file(public_path('vote.html'));
});

// Homepage - serve index.html for all other routes
Route::get('/{any?}', function () {
    return response()->file(public_path('index.html'));
})->where('any', '^(?!api|admin|vote).*$');
