<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', [App\Http\Controllers\TestController::class, 'index']);


use App\Http\Controllers\PaymentCallbackController;

Route::post('/payment/success', [PaymentCallbackController::class, 'success'])->name('payment.success');
Route::post('/payment/fail', [PaymentCallbackController::class, 'fail'])->name('payment.fail');
