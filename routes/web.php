<?php

declare(strict_types=1);

use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::get('/token', function () {
    return csrf_token();
});

Route::get('/', function () {
    return view('home');
});

Route::post('/shorten-url', [UrlController::class, 'shortenUrl'])
    ->name('shorten-url');

Route::post('/shorten-foldered-url', [UrlController::class, 'shortenFolderedUrl'])
    ->name('shorten-foldered-url');

Route::get('/{identifier}', [UrlController::class, 'redirectByIdentifier'])
    ->name('redirect');

Route::get('/{folder}/{identifier}', [UrlController::class, 'redirectByIdentifierAndFolder'])
    ->name('redirect-foldered');
