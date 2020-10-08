<?php

use App\Http\Controllers\Auth\AuthController;
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
    Route::post('/logout', [AuthController::class, 'logout']);
});

/**
 * Profile routes
 */
Route::middleware('auth:sanctum')->prefix('profiles')->group(function () {
    Route::patch('/avatar', [ProfileController::class, 'updateAvatar']);
    Route::delete('/avatar', [ProfileController::class, 'deleteAvatar']);
    Route::get('/', [ProfileController::class, 'show']);
    Route::get('/{id}', [ProfileController::class, 'show']);
    Route::patch('/', [ProfileController::class, 'update']);
});

/**
 * User routes
 */
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
