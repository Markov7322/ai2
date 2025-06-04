<x-admin-layout>
    <h1 class="text-xl font-bold mb-4">Create Category</h1>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block">Title</label>
            <input type="text" name="title" class="border rounded w-full" required>
        </div>
        <div>
            <label class="block">Description</label>
            <textarea name="description" class="border rounded w-full"></textarea>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded" type="submit">Save</button>
    </form>
</x-admin-layout>
