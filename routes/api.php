<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/get-user-list', [UserController::class, 'getUserList']);