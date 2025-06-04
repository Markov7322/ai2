<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    public function store(Request $request, Review $review)
    {
        $data = $request->validate([
            'type' => 'required|in:like,dislike',
        ]);

        $reaction = Reaction::where('user_id', Auth::id())
            ->where('review_id', $review->id)
            ->first();

        if ($reaction && $reaction->type === $data['type']) {
            $reaction->delete();
        } else {
            Reaction::updateOrCreate(
                ['user_id' => Auth::id(), 'review_id' => $review->id],
                ['type' => $data['type']]
            );
        }

        $likes = $review->reactions()->where('type', 'like')->count();
        $dislikes = $review->reactions()->where('type', 'dislike')->count();

        if ($request->expectsJson()) {
            return response()->json(['likes' => $likes, 'dislikes' => $dislikes]);
        }

        return redirect()->back();
    }
}
