<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ProjectsController;

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

    Route::get('/storage/{path}', function ($path) {
        $filePath = storage_path('app/public/' . $path);

        if (!File::exists($filePath)) {
            abort(404);
        }

        return response()->file($filePath);
    })->where('path', '.*');


    Route::prefix('teams')->group(function () {
        Route::get('index', [TeamController::class, 'index']);

        Route::post('store', [TeamController::class, 'store']);

        Route::get('show/{team}', [TeamController::class, 'show']);

        Route::post('update/{team}', [TeamController::class, 'update']);
        Route::delete('delete/{team}', [TeamController::class, 'destroy']);
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
