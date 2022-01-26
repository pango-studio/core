<?php

use Auth0\Login\Auth0Controller;
use Illuminate\Support\Facades\Route;
use Salt\Core\Http\Controllers\Auth\Auth0IndexController;

Route::get('/auth0/callback', [Auth0Controller::class, 'callback'])->name('auth0-callback');
Route::get('/login', [Auth0IndexController::class, 'login'])->name('login');
Route::get('/logout', [Auth0IndexController::class, 'logout'])->name('logout');
