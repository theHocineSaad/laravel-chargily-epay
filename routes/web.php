<?php

use Illuminate\Support\Facades\Route;
use TheHocineSaad\LaravelChargilyEPay\Http\Controllers\EpayWebhookController;

Route::middleware(['web'])->group(function () {
    Route::get('/epay-webhook-tester', [EpayWebhookController::class, 'test']);
    Route::post('/epay-webhook-tester', [EpayWebhookController::class, 'test_store'])->name('epaywebhook.post');
});
