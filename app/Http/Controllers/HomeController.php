<?php

namespace App\Http\Controllers;

use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Показывает главную страницу со списком категорий и объектов.
     */
    public function index()
    {
        // Загружаем только корневые категории с подкатегориями и объектами
        $categories = Category::with(['children.children', 'objects'])
            ->whereNull('parent_id')
            ->get();

        return view('home', compact('categories'));
    }
}
