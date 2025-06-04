<x-admin-layout>
    <div class="flex justify-between mb-4">
        <h1 class="text-xl font-bold">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="text-blue-500">Create</a>
    </div>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $category->id }}</td>
                <td class="px-4 py-2">{{ $category->title }}</td>
                <td class="px-4 py-2 space-x-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $categories->links() }}
</x-admin-layout>
