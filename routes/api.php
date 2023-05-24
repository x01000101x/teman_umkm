<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('/showPost', [PostController::class, 'index']);
    Route::get('/showPost/{id}', [PostController::class, 'showById']);
    Route::get('/order/{id}', [OrderController::class, 'index']);
    Route::post('/order/{id}', [OrderController::class, 'order']);


    Route::get('/logout', [UserController::class, 'logout']);
    Route::delete('/logoutById/{id}', [UserController::class, 'logoutById']);
});
Route::post('/reset_password', [UserController::class, 'validateResetPassword']);
Route::post('/confirm_new_password', [UserController::class, 'resetPassword']);
Route::post('/confirm_new_passwords/{token}', [UserController::class, 'confirmPassword']);
// Route::get('/send-mail',[UserController::class, 'mailsend']);

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

