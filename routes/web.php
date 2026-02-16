<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsletterController;

Route::get('/', [NewsletterController::class, 'index']);

Route::post('/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('subscribe');

Route::post('/unsubscribe', [NewsletterController::class, 'unsubscribe'])
    ->name('unsubscribe');
