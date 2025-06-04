<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewCommentController extends Controller
{
    /**
     * Сохраняет новый комментарий к отзыву.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $reviewId
     */
    public function store(Request $request, $reviewId)
    {
        // Проверяем авторизацию
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Войдите, чтобы оставить комментарий.');
        }

        // Находим отзыв
        $review = Review::findOrFail($reviewId);

        // Валидация контента комментария
        $data = $request->validate([
            'content' => 'required|string|min:3',
        ], [
            'content.required' => 'Поле комментария не может быть пустым.',
            'content.min'      => 'Комментарий слишком короткий.',
        ]);

        // Создаём комментарий
        Comment::create([
            'user_id'   => Auth::id(),
            'review_id' => $review->id,
            'content'   => $data['content'],
        ]);

        return redirect()
            ->route('objects.show', $review->object->slug)
            ->with('success', 'Комментарий успешно добавлен.');
    }
}
