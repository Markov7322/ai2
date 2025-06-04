<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(): View
    {
        $comments = Comment::with(['user', 'review'])->paginate(15);
        return view('admin.comments.index', compact('comments'));
    }

    public function edit(Comment $comment): View
    {
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $data = $request->validate([
            'content' => 'required|string',
        ]);
        $comment->update($data);
        return redirect()->route('admin.comments.index');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();
        return redirect()->route('admin.comments.index');
    }
}
