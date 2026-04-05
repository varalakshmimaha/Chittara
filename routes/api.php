<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminAuth;

// Public routes
Route::get('/public/data', [PublicController::class, 'data']);
Route::get('/captcha', [PublicController::class, 'captcha']);
Route::post('/vote', [PublicController::class, 'vote']);

// Admin auth (no middleware)
Route::post('/admin/login', [AdminController::class, 'login']);
Route::get('/admin/logout', [AdminController::class, 'logout']);

// Admin routes (require auth)
Route::middleware([AdminAuth::class])->prefix('admin')->group(function () {
    // Settings
    Route::get('/settings', [AdminController::class, 'getSettings']);
    Route::post('/settings', [AdminController::class, 'updateSettings']);
    Route::post('/upload/{type}', [AdminController::class, 'upload']);

    // Categories
    Route::get('/categories', [AdminController::class, 'getCategories']);
    Route::post('/categories', [AdminController::class, 'createCategory']);
    Route::match(['put', 'post'], '/categories/{id}', [AdminController::class, 'updateCategory']);
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory']);

    // Nominees
    Route::get('/nominees', [AdminController::class, 'getNominees']);
    Route::post('/nominees', [AdminController::class, 'createNominee']);
    Route::match(['put', 'post'], '/nominees/{id}', [AdminController::class, 'updateNominee']);
    Route::delete('/nominees/{id}', [AdminController::class, 'deleteNominee']);

    // Jury
    Route::get('/jury', [AdminController::class, 'getJury']);
    Route::post('/jury', [AdminController::class, 'createJury']);
    Route::match(['put', 'post'], '/jury/{id}', [AdminController::class, 'updateJury']);
    Route::delete('/jury/{id}', [AdminController::class, 'deleteJury']);

    // Partners
    Route::get('/partners', [AdminController::class, 'getPartners']);
    Route::post('/partners', [AdminController::class, 'createPartner']);
    Route::delete('/partners/{id}', [AdminController::class, 'deletePartner']);

    // Videos
    Route::get('/videos', [AdminController::class, 'getVideos']);
    Route::post('/videos', [AdminController::class, 'createVideo']);
    Route::delete('/videos/{id}', [AdminController::class, 'deleteVideo']);

    // Gallery
    Route::post('/gallery', [AdminController::class, 'createGallery']);
    Route::delete('/gallery/{id}', [AdminController::class, 'deleteGallery']);

    // Nav Links
    Route::get('/navlinks', [AdminController::class, 'getNavLinks']);
    Route::post('/navlinks', [AdminController::class, 'createNavLink']);
    Route::delete('/navlinks/{id}', [AdminController::class, 'deleteNavLink']);

    // Reports
    Route::get('/reports/stats', [AdminController::class, 'stats']);
    Route::get('/reports/by-category', [AdminController::class, 'byCategory']);
    Route::get('/reports/voters', [AdminController::class, 'voters']);
    Route::get('/reports/voter/{id}', [AdminController::class, 'voterDetail']);

    // Change password
    Route::post('/change-password', [AdminController::class, 'changePassword']);
});
