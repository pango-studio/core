<?php

use Illuminate\Support\Facades\Route;

use Salt\Core\Http\Controllers\Auth\Auth0IndexController;

Route::get('/login', [Auth0IndexController::class, 'login'])->name('login');
Route::get('/logout', [Auth0IndexController::class, 'logout'])->name('logout');
