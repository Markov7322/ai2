<x-admin-layout>
    <h1 class="text-xl font-bold mb-4">Reviews</h1>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">User</th>
                <th class="px-4 py-2">Category</th>
                <th class="px-4 py-2">Rating</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($reviews as $review)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $review->id }}</td>
                <td class="px-4 py-2">{{ $review->user->name }}</td>
                <td class="px-4 py-2">{{ $review->category->title }}</td>
                <td class="px-4 py-2">{{ $review->rating }}</td>
                <td class="px-4 py-2 space-x-2">
                    <a href="{{ route('admin.reviews.edit', $review) }}" class="text-indigo-600">Edit</a>
                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $reviews->links() }}
</x-admin-layout>
