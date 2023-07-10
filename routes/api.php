<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DividenController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\InvestController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
    // Route::post('/order/{id}', [PostController::class, 'search']);
    Route::get('/order/{id}', [OrderController::class, 'index']);
    Route::post('/order', [OrderController::class, 'order']);
    Route::post('/cart', [OrderController::class, 'getCart']);
    Route::get('/getByEmail', [OrderController::class, 'getByEmail']);


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
    Route::get('/admin/invests', [AdminController::class, 'invest']);
    Route::get('/admin/dividens', [AdminController::class, 'dividen']);
    Route::get('/admin/funds', [AdminController::class, 'fund']);
    Route::get('/admin/orders', [AdminController::class, 'order']);
    Route::get('/admin/users/{id}', [AdminController::class, 'userById']);
    Route::post('/admin/topup/{id}', [AdminController::class, 'isiSaldo']);
    Route::post('/admin/user_status/{id}', [AdminController::class, 'statusUser']);
    Route::post('/admin/dividen_status/{id}', [AdminController::class, 'dividenStatus']);
    Route::post('/admin/order_status/{id}', [AdminController::class, 'orderStatus']);
    Route::post('/admin/invest_status/{id}', [AdminController::class, 'investStatus']);
    Route::post('/admin/fund_status/{id}', [AdminController::class, 'fundStatus']);
    Route::post('/admin/add_post', [AdminController::class, 'addPost']);
    Route::post('/admin/getListCair', [AdminController::class, 'GetListCair']);
    Route::post('/admin/getListCair/{id}', [AdminController::class, 'GetListCairById']);
    Route::post('/admin/ChangeStatusCair/{id}', [AdminController::class, 'ChangeStatusCair']);
    Route::post('/admin/getListCairInvest', [AdminController::class, 'GetListCairInvest']);
    Route::post('/admin/getListCairInvest/{id}', [AdminController::class, 'GetListCairByIdInvest']);
    Route::post('/admin/ChangeStatusCairInvest/{id}', [AdminController::class, 'ChangeStatusCairInvest']);


    //Funds
    Route::get('/funds', [FundController::class, 'index']);
    Route::get('/funds/{id}', [FundController::class, 'getFundById']);
    Route::post('/funds/post', [FundController::class, 'create']);
    Route::post('/funds/post/{id}', [FundController::class, 'cair']);
    Route::post('/funds/getByEmail', [FundController::class, 'getByEmail']);
    Route::post('/funds/getByStatus', [FundController::class, 'getByStatus']);



    //Invest
    Route::get('/invest', [InvestController::class, 'index']);
    Route::get('/invests', [InvestController::class, 'invests']);
    Route::get('/invest/{id}', [InvestController::class, 'getInvestById']);
    Route::post('/invest/{id}', [InvestController::class, 'create']);
    Route::post('/calculator/{id}', [InvestController::class, 'calculator']);
    Route::post('/investCair/{id}', [InvestController::class, 'cair']);

    //Dividen
    Route::get('/dividen', [DividenController::class, 'index']);
    Route::post('/dividen/{id}', [DividenController::class, 'create']);

    //Message
    Route::get('/chats', [MessageController::class, 'index']);
    Route::get('/chats/{id}', [MessageController::class, 'getById']);
    Route::post('/chats/{id}', [MessageController::class, 'create']);

    //UNUSED
    //Chat
    Route::post('/message', [ChatController::class, 'message']);

});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

