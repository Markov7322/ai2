<x-admin-layout>
    <h1 class="text-xl font-bold mb-4">Reactions</h1>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">User</th>
                <th class="px-4 py-2">Review</th>
                <th class="px-4 py-2">Type</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($reactions as $reaction)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $reaction->id }}</td>
                <td class="px-4 py-2">{{ $reaction->user->name }}</td>
                <td class="px-4 py-2">{{ \Illuminate\Support\Str::limit($reaction->review->content, 30) }}</td>
                <td class="px-4 py-2">{{ $reaction->type }}</td>
                <td class="px-4 py-2">
                    <form action="{{ route('admin.reactions.destroy', $reaction) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600" onclick="return confirm('Delete?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $reactions->links() }}
</x-admin-layout>
