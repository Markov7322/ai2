<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Review;
use App\Models\Comment;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Отображает форму профиля (редактирование) и списки отзывов/комментариев.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Загружаем отзывы пользователя (approved = true) вместе с объектами
        $myReviews = Review::where('user_id', $user->id)
                           ->where('approved', true)
                           ->with('object')
                           ->orderBy('created_at', 'desc')
                           ->get();

        // Загружаем комментарии пользователя вместе с отзывами и объектами
        $myComments = Comment::where('user_id', $user->id)
                             ->with('review.object')
                             ->orderBy('created_at', 'desc')
                             ->get();

        return view('profile.edit', compact('user', 'myReviews', 'myComments'));
    }

    /**
     * Обновление информации пользователя (имя, email).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Удаляет аккаунт пользователя.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show(User $user): View
    {
        return view('profile.show', compact('user'));
    }
}
