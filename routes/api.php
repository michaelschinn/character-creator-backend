<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\UsersController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/log/{message}', function ($message) {
    Log::info("Hello my log, message: $message");
    return $message;
});
Route::group(['prefix' => 'v1'], function () {
    Route::post('/auth', [UsersController::class, 'login']);
    Route::post('/register', [UsersController::class, 'register']);
    Route::middleware('auth:api')->group(function () {
        Route::get('/logout', [UsersController::class, 'logout']);
        Route::post('/update/{id}', [UsersController::class, 'update']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        //CREATE
    });
});
