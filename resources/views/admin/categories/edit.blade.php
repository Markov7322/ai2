<x-admin-layout>
    <h1 class="text-xl font-bold mb-4">Edit Category</h1>
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block">Title</label>
            <input type="text" name="title" class="border rounded w-full" value="{{ $category->title }}" required>
        </div>
        <div>
            <label class="block">Description</label>
            <textarea name="description" class="border rounded w-full">{{ $category->description }}</textarea>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded" type="submit">Save</button>
    </form>
</x-admin-layout>
