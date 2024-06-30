<?php

use App\Http\Controllers\PaymentController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::resource('/terminal', App\Http\Controllers\Api\TerminalController::class);
Route::post('payment', [PaymentController::class, 'create'])->name('payment.create');
Route::post('payment/webhook/xendit', [PaymentController::class, 'webhook'])->name('payment.webhook');
Route::get('all-transactions', [PaymentController::class, 'getAllTransactions']);
Route::get('/check-payment-status', [App\Http\Controllers\PaymentController::class, 'checkPaymentStatus'])->name('checkPaymentStatus');
