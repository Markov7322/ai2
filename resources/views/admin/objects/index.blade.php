<x-admin-layout>
    <div class="flex justify-between mb-4">
        <h1 class="text-xl font-bold">Objects</h1>
        <a href="{{ route('admin.objects.create') }}" class="text-blue-500">Create</a>
    </div>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Category</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($objects as $object)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $object->id }}</td>
                <td class="px-4 py-2">{{ $object->title }}</td>
                <td class="px-4 py-2">{{ $object->category->title }}</td>
                <td class="px-4 py-2 space-x-2">
                    <a href="{{ route('admin.objects.edit', $object) }}" class="text-indigo-600">Edit</a>
                    <form action="{{ route('admin.objects.destroy', $object) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $objects->links() }}
</x-admin-layout>
