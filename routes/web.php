<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsletterController;

Route::get('/', [NewsletterController::class, 'index']);

Route::post('/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('subscribe');

Route::get('/unsubscribe/{email}', [NewsletterController::class, 'unsubscribe'])
    ->name('unsubscribe');

Route::post('/check-status', [NewsletterController::class, 'checkStatus'])
    ->name('check.status');

Route::get('/dashboard', [NewsletterController::class, 'dashboard'])
    ->name('dashboard');

Route::get('/export-subscribers', [NewsletterController::class, 'exportSubscribers'])
    ->name('export.subscribers');

Route::post('/bulk-unsubscribe', [NewsletterController::class, 'bulkUnsubscribe'])
    ->name('bulk.unsubscribe');
