<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Comment;
use App\Models\User;
use App\Models\Category;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'reviews' => Review::count(),
            'comments' => Comment::count(),
            'users' => User::count(),
            'categories' => Category::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
