<x-admin-layout>
    <h1 class="text-xl font-bold mb-4">Comments</h1>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">User</th>
                <th class="px-4 py-2">Review</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($comments as $comment)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $comment->id }}</td>
                <td class="px-4 py-2">{{ $comment->user->name }}</td>
                <td class="px-4 py-2">{{ \Illuminate\Support\Str::limit($comment->review->content, 30) }}</td>
                <td class="px-4 py-2 space-x-2">
                    <a href="{{ route('admin.comments.edit', $comment) }}" class="text-indigo-600">Edit</a>
                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $comments->links() }}
</x-admin-layout>
