<?php

use App\Http\Controllers\Api\Admin\ManageUserController;
use App\Http\Controllers\Api\Admin\LocationController;
use App\Http\Controllers\Api\Admin\EventController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

app('request')->headers->set('Accept', 'application/json');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::prefix('admin')->middleware('auth:sanctum')->name('admin.')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Users
    Route::apiResource('/users', ManageUserController::class)->parameters([
        'users' => 'user'
    ]);

    // Locations
    Route::apiResource('/locations', LocationController::class)->parameters([
        'locations' => 'location'
    ]);

    // Events
    Route::apiResource('/events', EventController::class)->parameters([
        'events' => 'event'
    ]);
});
