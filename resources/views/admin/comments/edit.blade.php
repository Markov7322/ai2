<x-admin-layout>
    <h1 class="text-xl font-bold mb-4">Edit Comment</h1>
    <form method="POST" action="{{ route('admin.comments.update', $comment) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block">Content</label>
            <textarea name="content" class="border rounded w-full">{{ $comment->content }}</textarea>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded" type="submit">Save</button>
    </form>
</x-admin-layout>
