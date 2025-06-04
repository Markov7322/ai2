<x-admin-layout>
    <h1 class="text-xl font-bold mb-4">Edit Review</h1>
    <form method="POST" action="{{ route('admin.reviews.update', $review) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block">Content</label>
            <textarea name="content" class="border rounded w-full">{{ $review->content }}</textarea>
        </div>
        <div>
            <label class="block">Rating</label>
            <input type="number" name="rating" value="{{ $review->rating }}" class="border rounded w-full">
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded" type="submit">Save</button>
    </form>
</x-admin-layout>
