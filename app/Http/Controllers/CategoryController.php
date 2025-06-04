<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)
            ->with([
                'children',
                'reviews.user',
                'reviews.reactions',
                'reviews.comments.user',
                'reviews.comments.replies.user',
            ])
            ->firstOrFail();

        return view('categories.show', compact('category'));
    }

    public function storeReview(Request $request, string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        if ($category->children()->exists()) {
            return redirect()->back()->withErrors(['category' => 'Нельзя оставлять отзывы в этой категории.']);
        }

        $validated = $request->validate([
            'content' => 'required|string|min:10|max:2000',
            'rating'  => 'required|integer|min:1|max:5',
            'image'   => 'nullable|image|max:2048',
        ]);

        $review = Review::create([
            'user_id'     => Auth::id(),
            'category_id' => $category->id,
            'content'     => $validated['content'],
            'rating'      => $validated['rating'],
            'status'      => 'pending',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reviews', 'public');
            $review->image_path = $path;
            $review->save();
        }

        return redirect()->route('categories.show', $category->slug)
            ->with('success', 'Спасибо! Ваш отзыв успешно добавлен.');
    }
}
