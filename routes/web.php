<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewObjectController;
use App\Http\Controllers\ReviewCommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1) Главная страница (список категорий и объектов)
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2) Просмотр отдельного объекта
Route::get('/objects/{slug}', [ReviewObjectController::class, 'show'])
     ->name('objects.show');

// 3) Сохранение нового отзыва (POST), доступно только авторизованным
Route::post('/objects/{slug}/reviews', [ReviewObjectController::class, 'storeReview'])
     ->middleware('auth')
     ->name('objects.reviews.store');

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
});

// 8) В самом низу подключаем маршруты аутентификации Breeze (login/register/logout)
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
