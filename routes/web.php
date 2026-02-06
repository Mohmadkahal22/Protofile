<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Main Website Routes (all data loaded via Axios from API)
Route::get('/', [App\Http\Controllers\WebsiteController::class, 'index'])->name('home');
Route::get('/projects', [App\Http\Controllers\WebsiteController::class, 'projects'])->name('projects');
Route::get('/project/{id}', [App\Http\Controllers\WebsiteController::class, 'projectDetail'])->name('project.detail');
Route::get('/team/{id}', [App\Http\Controllers\WebsiteController::class, 'teamDetail'])->name('team.detail');

// API Documentation
Route::get('/api-docs', [App\Http\Controllers\LandingController::class, 'index'])->name('api.docs');

// ═══ Admin Panel (all data via Axios + existing API routes) ═══
Route::get('/admin', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/teams', [AdminController::class, 'teams'])->name('admin.teams');
    Route::get('/services', [AdminController::class, 'services'])->name('admin.services');
    Route::get('/projects', [AdminController::class, 'projects'])->name('admin.projects');
    Route::get('/about', [AdminController::class, 'about'])->name('admin.about');
    Route::get('/faq', [AdminController::class, 'faq'])->name('admin.faq');
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('admin.reviews');
    Route::get('/videos', [AdminController::class, 'videos'])->name('admin.videos');
    Route::get('/contacts', [AdminController::class, 'contacts'])->name('admin.contacts');
});

Auth::routes();
