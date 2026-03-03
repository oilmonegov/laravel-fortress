<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::post('/login', [AuthController::class, 'login']);

Route::get('/admin/users', [UserController::class, 'index']);
