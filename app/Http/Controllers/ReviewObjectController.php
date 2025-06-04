<?php

namespace App\Http\Controllers;

use App\Models\ReviewObject;
use App\Models\Review;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReviewObjectController extends Controller
{
    /**
     * Show form to create new review object and review.
     */
    public function create()
    {
        $categories = Category::with('children')->get();
        return view('objects.create', compact('categories'));
    }

    /**
     * Store new review object with first review.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'        => 'required|exists:categories,id',
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'object_image'       => 'nullable|image|max:2048',

            'review_title'       => 'nullable|string|max:255',
            'review_content'     => 'required|string|min:10|max:2000',
            'pros'               => 'nullable|string|max:2000',
            'cons'               => 'nullable|string|max:2000',
            'rating'             => 'required|integer|min:1|max:5',
            'review_image'       => 'nullable|image|max:2048',
        ]);

        $category = Category::findOrFail($validated['category_id']);
        if ($category->children()->exists()) {
            return back()->withErrors(['category_id' => 'Выберите подкатегорию.']);
        }

        if (ReviewObject::where('user_id', Auth::id())
            ->where('category_id', $category->id)->exists()) {
            return back()->withErrors(['category_id' => 'Вы уже создали объект в этой категории.']);
        }

        $object = new ReviewObject([
            'category_id' => $category->id,
            'user_id'     => Auth::id(),
            'title'       => $validated['title'],
            'slug'        => Str::slug($validated['title']) . '-' . uniqid(),
            'description' => $validated['description'] ?? null,
            'status'      => 'pending',
        ]);
        $object->save();

        if ($request->hasFile('object_image')) {
            $path = $request->file('object_image')->store('objects', 'public');
            $object->image_path = $path;
            $object->save();
        }

        $review = new Review([
            'user_id'          => Auth::id(),
            'review_object_id' => $object->id,
            'title'            => $validated['review_title'] ?? null,
            'content'          => $validated['review_content'],
            'pros'             => $validated['pros'] ?? null,
            'cons'             => $validated['cons'] ?? null,
            'rating'           => $validated['rating'],
            'status'           => 'pending',
        ]);
        $review->save();

        if ($request->hasFile('review_image')) {
            $path = $request->file('review_image')->store('reviews', 'public');
            $review->image_path = $path;
            $review->save();
        }

        return redirect()->route('objects.show', $object->slug)
            ->with('success', 'Объект и отзыв созданы, ждут модерации.');
    }
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
            'title'   => 'required|string|max:255',
            'content' => 'required|string|min:10|max:2000',
            'pros'    => 'nullable|string|max:2000',
            'cons'    => 'nullable|string|max:2000',
            'rating'  => 'required|integer|min:1|max:5',
            'image'   => 'nullable|image|max:2048',
        ]);

        if ($object->reviews()->where('user_id', Auth::id())->exists()) {
            return redirect()->route('objects.show', ['slug' => $slug])
                ->with('error', 'Вы уже оставили отзыв на этот объект.');
        }

        // Сохраняем новый отзыв
        $review = new Review([
            'user_id'           => Auth::id(),
            'review_object_id'  => $object->id,
            'title'            => $validated['title'],
            'content'           => $validated['content'],
            'pros'              => $validated['pros'] ?? null,
            'cons'              => $validated['cons'] ?? null,
            'rating'            => $validated['rating'],
            'status'            => 'pending',
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
