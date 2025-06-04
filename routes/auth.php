<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes (Breeze)
|--------------------------------------------------------------------------
|
| Здесь определяются все маршруты для регистрации, логина, сброса пароля,
| подтверждения электронной почты и т. д. (имена: login, register, logout и т.д.).
| В файле routes/web.php должен быть подключён этот файл командой:
|     require __DIR__.'/auth.php';
|
*/

// Маршруты, доступные только гостям (guest)
Route::middleware('guest')->group(function () {
    // Регистрация
    Route::get('register', [RegisteredUserController::class, 'create'])
         ->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Логин
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
         ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Сброс пароля — запрос ссылки
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
         ->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
         ->name('password.email');

    // Сброс пароля — форма ввода нового пароля
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
         ->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])
         ->name('password.store');
});

// Маршруты, доступные только авторизованным пользователям (auth)
Route::middleware('auth')->group(function () {
    // Подтверждение email — страница, сообщающая “пожалуйста, подтвердите почту”
    Route::get('verify-email', EmailVerificationPromptController::class)
         ->name('verification.notice');

    // Обработка клика по ссылке из письма: подтверждение email
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
         ->middleware(['signed', 'throttle:6,1'])
         ->name('verification.verify');

    // Повторная отправка письма для подтверждения почты
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
         ->middleware('throttle:6,1')
         ->name('verification.send');

    // Подтверждение пароля (прежде чем изменить важные данные)
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
         ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Изменение пароля
    Route::put('password', [PasswordController::class, 'update'])
         ->name('password.update');

    // Выход (logout)
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
         ->name('logout');
});
