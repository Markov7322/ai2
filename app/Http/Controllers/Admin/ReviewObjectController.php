<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReviewObject;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ReviewObjectController extends Controller
{
    public function index(): View
    {
        $objects = ReviewObject::with('category')->paginate(15);
        return view('admin.objects.index', compact('objects'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('admin.objects.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'image_path' => 'nullable|string',
        ]);
        $data['slug'] = Str::slug($data['title']);
        ReviewObject::create($data);
        return redirect()->route('admin.objects.index');
    }

    public function edit(ReviewObject $object): View
    {
        $categories = Category::all();
        return view('admin.objects.edit', compact('object', 'categories'));
    }

    public function update(Request $request, ReviewObject $object): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
            'image_path' => 'nullable|string',
        ]);
        $data['slug'] = Str::slug($data['title']);
        $object->update($data);
        return redirect()->route('admin.objects.index');
    }

    public function destroy(ReviewObject $object): RedirectResponse
    {
        $object->delete();
        return redirect()->route('admin.objects.index');
    }
}
