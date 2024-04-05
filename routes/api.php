<?php

use App\Http\Controllers\Api\Admin\ManageUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

app('request')->headers->set('Accept', 'application/json');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::prefix('admin')->middleware('auth:sanctum')->name('admin.')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User
    // Route::get('/users', [ManageUserController::class, 'index'])->name('user.index');
    // Route::post('/users', [ManageUserController::class, 'store'])->name('user.store');
    // Route::patch('/users/{user}', [ManageUserController::class, 'update'])->name('user.update');
    // Route::delete('/users/{user}', [ManageUserController::class, 'destroy'])->name('user.destroy');
    Route::apiResource('/users', ManageUserController::class)->parameters([
        'users' => 'user'
    ]);
});

