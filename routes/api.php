<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Collection\CollectionController;
use App\Http\Controllers\OpenGraph\OpenGraphController;
use App\Http\Controllers\Profile\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Auth routes
 */
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

/**
 * Profile routes
 */
Route::middleware('auth:sanctum')->prefix('profiles')->group(function () {
    Route::post('/avatar', [ProfileController::class, 'updateAvatar']);
    Route::delete('/avatar', [ProfileController::class, 'deleteAvatar']);
    Route::get('/', [ProfileController::class, 'show']);
    Route::get('/{id}', [ProfileController::class, 'show']);
    Route::patch('/', [ProfileController::class, 'update']);
});

/**
 * OpenGraph routes
 */
Route::get('/opengraph/preview', [OpenGraphController::class, 'preview']);

/**
 * Collections routes
 */
Route::middleware('auth:sanctum')->prefix('collections')->group(function () {
    Route::post('/', [CollectionController::class, 'store']);
    Route::post('/{model}/image', [CollectionController::class, 'updateImage']);
    Route::delete('/{model}/image', [CollectionController::class, 'deleteImage']);
    Route::patch('/{model}', [CollectionController::class, 'update']);
    Route::delete('/{model}', [CollectionController::class, 'delete']);
    Route::get('/', [CollectionController::class, 'index']);
    Route::get('/{user}', [CollectionController::class, 'index']);
});

/**
 * User routes
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
