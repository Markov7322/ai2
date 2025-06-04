<?php

namespace App\Http\Controllers;

use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Показывает главную страницу со списком категорий.
     */
    public function index()
    {
        // Загружаем корневые категории с подкатегориями
        $categories = Category::with('children.children')
            ->whereNull('parent_id')
            ->get();

        return view('home', compact('categories'));
    }
}
