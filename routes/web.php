<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewCommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\InstallController;

if (!env('APP_INSTALLED', false)) {
    Route::get('/install', [InstallController::class, 'show'])->name('install.show');
    Route::post('/install', [InstallController::class, 'store'])->name('install.store');
    Route::fallback(fn () => redirect('/install'));
    return;
}

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1) Главная страница (список категорий)
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2) Просмотр категории
Route::get('/categories/{slug}', [CategoryController::class, 'show'])
     ->name('categories.show');

// 3) Добавление отзыва в категорию (только для листовых)
Route::post('/categories/{slug}/reviews', [CategoryController::class, 'storeReview'])
     ->middleware('auth')
     ->name('categories.reviews.store');

// 4) Сохранение комментария к отзыву (POST), только для авторизованных
Route::post('/reviews/{review}/comments', [ReviewCommentController::class, 'store'])
     ->middleware('auth')
     ->name('reviews.comments.store');

// 4.1) Добавление лайка/дизлайка к отзыву
Route::post('/reviews/{review}/react', [ReactionController::class, 'store'])
     ->middleware('auth')
     ->name('reviews.react');

// 5) Страница “Помощь”
Route::get('/help', function () {
    return view('help');
})->name('help');

// 6) Страница “Промоакции”
Route::get('/promo', function () {
    return view('promo');
})->name('promo');

// Публичный профиль пользователя
Route::get('/users/{user}', [ProfileController::class, 'show'])->name('users.show');

// 7) Защищённые маршруты (личный кабинет, дашборд)
Route::middleware(['auth'])->group(function () {
    // 7.1) Дашборд
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // 7.2) Профиль пользователя (редактирование, просмотр отзывов/комментариев)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 7.3) Обмен сообщениями
    Route::get('/messages', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages/start/{user}', [\App\Http\Controllers\MessageController::class, 'start'])->name('messages.start');
    Route::get('/messages/{conversation}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::get('/messages/{conversation}/updates', [\App\Http\Controllers\MessageController::class, 'updates'])->name('messages.updates');
    Route::post('/messages/{conversation}', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');
});

// 8) В самом низу подключаем маршруты аутентификации Breeze (login/register/logout)
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
