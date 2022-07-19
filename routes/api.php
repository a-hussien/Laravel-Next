<?php

use App\Http\Controllers\UserController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/user', [UserController::class, 'store']);
    Route::post('/login', [UserController::class, 'signin']);
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user', [UserController::class, 'show']);
        Route::post('/logout', [UserController::class, 'signout']);
    });
});
