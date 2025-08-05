<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'login']);


    Route::get('/storage/{path}', function ($path) {
        $filePath = storage_path('app/public/' . $path);

        if (!File::exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath);
    })->where('path', '.*');


    Route::prefix('teams')->group(function () {
        Route::get('index', [TeamController::class, 'index']);

        Route::post('store', [TeamController::class, 'store'])->middleware('admin');

        Route::get('show/{team}', [TeamController::class, 'show']);

        Route::post('update/{team}', [TeamController::class, 'update'])->middleware('admin');
        Route::delete('delete/{team}', [TeamController::class, 'destroy'])->middleware('admin');
    });



    Route::prefix('services')->group(function () {
        Route::get('index', [ServicesController::class, 'index']);
        Route::post('store', [ServicesController::class, 'store']);
        Route::get('show/{team}', [ServicesController::class, 'show']);
        Route::post('update/{team}', [ServicesController::class, 'update']);
        Route::delete('delete/{team}', [ServicesController::class, 'destroy']);
    });


    Route::prefix('projects')->group(function () {
        Route::get('index', [ProjectsController::class, 'index']);
        Route::post('store', [ProjectsController::class, 'store']);
        Route::get('show/{team}', [ProjectsController::class, 'show']);
        Route::post('update/{team}', [ProjectsController::class, 'update']);
        Route::delete('delete/{team}', [ProjectsController::class, 'destroy']);
    });



    Route::prefix('about_us')->group(function () {
        Route::get('index', [AboutUsController::class, 'index']);
        Route::post('store', [AboutUsController::class, 'store']);
        Route::get('show/', [AboutUsController::class, 'show']);
        Route::post('update/', [AboutUsController::class, 'update']);
        Route::delete('delete', [AboutUsController::class, 'destroy']);
    });



    Route::prefix('contact_us')->group(function () {
        Route::get('index', [ContactUsController::class, 'index']);
        Route::post('store', [ContactUsController::class, 'store']);
        Route::get('show/{id}', [ContactUsController::class, 'show']);
        Route::put('update/{id}', [ContactUsController::class, 'update']);
        Route::delete('delete/{id}', [ContactUsController::class, 'destroy']);
    });


    Route::prefix('faq')->group(function () {
        Route::get('index', [FAQController::class, 'index']);
        Route::post('store', [FAQController::class, 'store']);
        Route::get('show/{id}', [FAQController::class, 'show']);
        Route::put('update/{id}', [FAQController::class, 'update']);
        Route::delete('delete/{id}', [FAQController::class, 'destroy']);
    });


    Route::prefix('review')->group(function () {
        Route::get('index', [ReviewController::class, 'index']);
        Route::post('store', [ReviewController::class, 'store']);
        Route::get('show/{id}', [ReviewController::class, 'show']);
        Route::put('update/{id}', [ReviewController::class, 'update']);
        Route::delete('delete/{id}', [ReviewController::class, 'destroy']);
    });


    Route::prefix('video')->group(function () {
        Route::get('index', [VideoController::class, 'index']);
        Route::post('store', [VideoController::class, 'store']);
        Route::get('show/{id}', [VideoController::class, 'show']);
        Route::put('update/{id}', [VideoController::class, 'update']);
        Route::delete('delete/{id}', [VideoController::class, 'destroy']);
    });
