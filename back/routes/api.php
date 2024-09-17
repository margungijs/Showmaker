<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PaymentController;

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

Route::prefix('/v1')->group(function() {

    Route::prefix('/Event')->group(function() {
        Route::post('/', [EventController::class, 'store']);
        Route::get('/', [EventController::class, 'events']);
        Route::post('/{event_id}', [EventController::class, 'images']);
        Route::get('/{event_id}', [EventController::class, 'event']);
    });

//    ->middleware('route.auth')

    Route::prefix('/Purchase')->group(function() {
        //Route::post('/', [PaymentController::class, 'sendMail']);
        Route::post('/', [PaymentController::class, 'test']);
        Route::get('/{user_id}', [PaymentController::class, 'payedEvents']);
    });

    Route::prefix('/Comment')->group(function() {
        Route::post('/{user}', [CommentController::class, 'store']);
    });



    Route::prefix('/User')->group(function() {
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{user_id}', [PaymentController::class, 'payedEvents']);
    });

    Route::prefix('/Auth')->group(function() {
        Route::post('/', [UserController::class, 'login']);
    });

    Route::middleware(\App\Http\Middleware\CorsMiddleware::class)->group(function() {
        Route::prefix('/Pay')->group(function() {
            Route::post('/', [PaymentController::class, 'createPaymentIntent']);
        });
    });
});
