<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PostController;

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

Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [LoginController::class, 'register']);
    
  /*  Route::middleware('auth:api')->group(function () {
        Route::resource('posts', PostController::class);
    });*/

    //delete user with param
    Route::delete('/delete-user/{id}', [LoginController::class, 'deleteUser']);
    //secure get api for fetch users 
    Route::get('/users-list', [LoginController::class, 'usersList']);
});