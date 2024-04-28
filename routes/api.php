<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::resource('/terminal', App\Http\Controllers\Api\TerminalController::class);
Route::post('/payment', [App\Http\Controllers\PaymentController::class, 'create'])->name('payment.create');
Route::post('/payment/webhook/xendit', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');

