<?php

namespace App\Http\Controllers;

use App\Models\ReviewObject;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewObjectController extends Controller
{
    /**
     * Показывает страницу объекта вместе с отзывами
     */
    public function show($slug)
    {
        // Ищем объект по slug
        $object = ReviewObject::where('slug', $slug)->first();

        if (! $object) {
            abort(404);
        }

        // Загружаем отзывы сразу с пользователями (авторами)
        $object->load([
            'reviews' => function($q) {
                $q->with(['user', 'reactions'])->orderBy('created_at', 'desc');
            }
        ]);

        return view('objects.show', compact('object'));
    }

    /**
     * Обрабатывает POST-запрос с новым отзывом
     */
    public function storeReview(Request $request, $slug)
    {
        $object = ReviewObject::where('slug', $slug)->first();
        if (! $object) {
            abort(404);
        }

        // Валидация
        $validated = $request->validate([
            'content' => 'required|string|min:10|max:2000',
            'rating'  => 'required|integer|min:1|max:5',
            'image'   => 'nullable|image|max:2048',
        ]);

        // Сохраняем новый отзыв
        $review = new Review([
            'user_id'           => Auth::id(),
            'review_object_id'  => $object->id,
            'content'           => $validated['content'],
            'rating'            => $validated['rating'],
        ]);
        $review->save();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reviews', 'public');
            $review->image_path = $path;
            $review->save();
        }

        // Пересчёт среднего рейтинга и количества отзывов
        $allRatings = $object->reviews()->pluck('rating')->toArray();
        $average = count($allRatings) ? array_sum($allRatings) / count($allRatings) : 0;
        $object->avg_rating    = round($average, 1);
        $object->reviews_count = count($allRatings);
        $object->save();

        // Редирект назад с флешем
        return redirect()
            ->route('objects.show', ['slug' => $slug])
            ->with('success', 'Спасибо! Ваш отзыв успешно добавлен.');
    }
}
