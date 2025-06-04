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
use App\Models\Reaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $reviews = Review::where('user_id', $user->id)
            ->with('object')
            ->orderBy('created_at', 'desc')
            ->get();

        $comments = Comment::where('user_id', $user->id)
            ->with('review.object')
            ->orderBy('created_at', 'desc')
            ->get();

        $reputation = Reaction::join('reviews', 'reactions.review_id', '=', 'reviews.id')
            ->where('reviews.user_id', $user->id)
            ->selectRaw("SUM(CASE WHEN reactions.type = 'like' THEN 1 WHEN reactions.type = 'dislike' THEN -1 ELSE 0 END) as score")
            ->value('score') ?? 0;

        $followersCount = 0; // система подписок не реализована

        $lastActivityTimestamp = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderByDesc('last_activity')
            ->value('last_activity');
        $lastActivity = $lastActivityTimestamp ? Carbon::createFromTimestamp($lastActivityTimestamp) : null;

        return view('profile.show', compact('user', 'reviews', 'comments', 'reputation', 'followersCount', 'lastActivity'));
    }
}
