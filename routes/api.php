<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FundController;
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


    //Artikel
    Route::get('/showPost', [PostController::class, 'index']);
    Route::get('/showPost/{id}', [PostController::class, 'showById']);
    Route::post('/showPost', [PostController::class, 'showById']);
    Route::post('/order/{id}', [PostController::class, 'search']);
    Route::get('/order/{id}', [OrderController::class, 'index']);


    //Logout
    Route::get('/logout', [UserController::class, 'logout']);
    Route::delete('/logoutById/{id}', [UserController::class, 'logoutById']);


    //Reset Password
    Route::post('/reset_password', [UserController::class, 'validateResetPassword']);
    Route::post('/confirm_new_passwords/{token}', [UserController::class, 'confirmPassword']);
    // Route::post('/confirm_new_password', [UserController::class, 'resetPassword']);
    // Route::get('/send-mail',[UserController::class, 'mailsend']);

    //Admin
    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::get('/admin/users/{id}', [AdminController::class, 'userById']);
    Route::post('/admin/topup/{id}', [AdminController::class, 'isiSaldo']);

    //Funds
    Route::get('/funds', [FundController::class, 'index']);
    Route::post('/funds/post', [FundController::class, 'create']);

});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

