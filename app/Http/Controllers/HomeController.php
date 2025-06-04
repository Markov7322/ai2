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
        // Загружаем все категории вместе с объектами (чтобы не было N+1)
        $categories = Category::with('objects')->get();

        return view('home', compact('categories'));
    }
}
