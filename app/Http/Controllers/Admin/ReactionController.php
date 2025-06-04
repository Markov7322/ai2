<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reaction;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ReactionController extends Controller
{
    public function index(): View
    {
        $reactions = Reaction::with(['user', 'review'])->paginate(15);
        return view('admin.reactions.index', compact('reactions'));
    }

    public function destroy(Reaction $reaction): RedirectResponse
    {
        $reaction->delete();
        return redirect()->route('admin.reactions.index');
    }
}
