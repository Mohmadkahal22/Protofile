<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\Registration\RegisterController;
use App\Http\Controllers\Registration\LoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [LoginController::class, 'login']);

// Storage
Route::get('/storage/{path}', function ($path) {
    if (!Storage::disk('public')->exists($path)) {
        abort(404, 'File not found');
    }
    return response()->file(Storage::disk('public')->path($path));
})->where('path', '.*')->name('api.storage');

/*
|--------------------------------------------------------------------------
| Public API Routes (no auth required - used by website via Axios)
| Public GET endpoints cached for 5 minutes in the browser
|--------------------------------------------------------------------------
*/

// Teams
Route::prefix('teams')->group(function () {
    Route::get('index', [TeamController::class, 'index'])->middleware('cache.headers:public;max_age=300;etag');
    Route::get('show/{team}', [TeamController::class, 'show'])->middleware('cache.headers:public;max_age=300;etag');

    Route::post('store', [TeamController::class, 'store'])->middleware(['auth:sanctum', 'admin']);
    Route::post('update/{team}', [TeamController::class, 'update'])->middleware(['auth:sanctum', 'admin']);
    Route::delete('delete/{team}', [TeamController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);
});

// Services
Route::prefix('services')->group(function () {
    Route::get('index', [ServicesController::class, 'index'])->middleware('cache.headers:public;max_age=300;etag');
    Route::get('show/{id}', [ServicesController::class, 'show'])->middleware('cache.headers:public;max_age=300;etag');

    Route::post('store', [ServicesController::class, 'store'])->middleware(['auth:sanctum', 'admin']);
    Route::post('update/{id}', [ServicesController::class, 'update'])->middleware(['auth:sanctum', 'admin']);
    Route::delete('delete/{id}', [ServicesController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);
});

// Projects
Route::prefix('projects')->group(function () {
    Route::get('index', [ProjectsController::class, 'index'])->middleware('cache.headers:public;max_age=300;etag');
    Route::get('show/{id}', [ProjectsController::class, 'show'])->middleware('cache.headers:public;max_age=300;etag');

    Route::post('store', [ProjectsController::class, 'store'])->middleware(['auth:sanctum', 'admin']);
    Route::post('update/{id}', [ProjectsController::class, 'update'])->middleware(['auth:sanctum', 'admin']);
    Route::delete('delete/{id}', [ProjectsController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);
});

// About Us
Route::prefix('about_us')->group(function () {
    Route::get('index', [AboutUsController::class, 'index'])->middleware('cache.headers:public;max_age=300;etag');
    Route::get('show', [AboutUsController::class, 'show'])->middleware('cache.headers:public;max_age=300;etag');

    Route::post('store', [AboutUsController::class, 'store'])->middleware(['auth:sanctum', 'admin']);
    Route::post('update', [AboutUsController::class, 'update'])->middleware(['auth:sanctum', 'admin']);
    Route::delete('delete', [AboutUsController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);
});

// Contact Us (store is public - anyone can send a message)
Route::prefix('contact_us')->group(function () {
    Route::post('store', [ContactUsController::class, 'store']);

    Route::get('index', [ContactUsController::class, 'index'])->middleware(['auth:sanctum', 'admin']);
    Route::get('show/{id}', [ContactUsController::class, 'show'])->middleware(['auth:sanctum', 'admin']);
    Route::put('update/{id}', [ContactUsController::class, 'update'])->middleware(['auth:sanctum', 'admin']);
    Route::delete('delete/{id}', [ContactUsController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);
});

// FAQ
Route::prefix('faq')->group(function () {
    Route::get('index', [FAQController::class, 'index'])->middleware('cache.headers:public;max_age=300;etag');
    Route::get('show/{id}', [FAQController::class, 'show'])->middleware('cache.headers:public;max_age=300;etag');

    Route::post('store', [FAQController::class, 'store'])->middleware(['auth:sanctum', 'admin']);
    Route::put('update/{id}', [FAQController::class, 'update'])->middleware(['auth:sanctum', 'admin']);
    Route::delete('delete/{id}', [FAQController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);
});

// Reviews (store is public - anyone can submit a review)
Route::prefix('review')->group(function () {
    Route::get('index', [ReviewController::class, 'index'])->middleware('cache.headers:public;max_age=300;etag');
    Route::get('show/{id}', [ReviewController::class, 'show'])->middleware('cache.headers:public;max_age=300;etag');
    Route::post('store', [ReviewController::class, 'store']);

    Route::put('update/{id}', [ReviewController::class, 'update'])->middleware(['auth:sanctum', 'admin']);
    Route::delete('delete/{id}', [ReviewController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);
});

// Videos
Route::prefix('video')->group(function () {
    Route::get('index', [VideoController::class, 'index'])->middleware('cache.headers:public;max_age=300;etag');
    Route::get('show/{id}', [VideoController::class, 'show'])->middleware('cache.headers:public;max_age=300;etag');

    Route::post('store', [VideoController::class, 'store'])->middleware(['auth:sanctum', 'admin']);
    Route::put('update/{id}', [VideoController::class, 'update'])->middleware(['auth:sanctum', 'admin']);
    Route::delete('delete/{id}', [VideoController::class, 'destroy'])->middleware(['auth:sanctum', 'admin']);
});
