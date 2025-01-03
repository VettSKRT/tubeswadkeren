<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [UserController::class, 'index'])->name('users.index');
Route::post('/users/{id}/add', [UserController::class, 'addFriend'])->name('users.add-friend');
Route::post('/users/{id}/accept', [UserController::class, 'acceptFriend'])->name('users.accept-friend');
Route::post('/users/{id}/reject', [UserController::class, 'rejectFriend'])->name('users.reject-friend');
Route::post('/users/{id}/not-interested', [UserController::class, 'notInterested'])->name('users.not-interested');