<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('app'));

// Catch-all para que el SPA Blade maneje todas las rutas internas
Route::get('/{any}', fn () => view('app'))->where('any', '.*');
