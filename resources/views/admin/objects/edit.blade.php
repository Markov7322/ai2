<x-admin-layout>
    <h1 class="text-xl font-bold mb-4">Edit Object</h1>
    <form method="POST" action="{{ route('admin.objects.update', $object) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block">Title</label>
            <input type="text" name="title" class="border rounded w-full" value="{{ $object->title }}" required>
        </div>
        <div>
            <label class="block">Category</label>
            <select name="category_id" class="border rounded w-full">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected($object->category_id == $category->id)>{{ $category->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block">Description</label>
            <textarea name="description" class="border rounded w-full">{{ $object->description }}</textarea>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded" type="submit">Save</button>
    </form>
</x-admin-layout>
