<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ReviewObjectController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ReactionController;
use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('objects', ReviewObjectController::class);
    Route::resource('reviews', ReviewController::class)->except(['create','store','show']);
    Route::resource('comments', CommentController::class)->except(['create','store','show']);
    Route::resource('reactions', ReactionController::class)->only(['index','destroy']);
    Route::resource('users', UserController::class)->except(['create','store','show']);
});
