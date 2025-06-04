<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::with(['user', 'object'])->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function edit(Review $review): View
    {
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'status' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        $review->update($data);
        return redirect()->route('admin.reviews.index');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return redirect()->route('admin.reviews.index');
    }
}
