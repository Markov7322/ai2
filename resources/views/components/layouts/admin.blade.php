<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin</title>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 flex">
    <aside class="w-64 bg-white border-r">
        <div class="p-4 font-bold">Admin</div>
        <nav class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block">Dashboard</a>
            <a href="{{ route('admin.users.index') }}" class="block">Users</a>
            <a href="{{ route('admin.categories.index') }}" class="block">Categories</a>
            <a href="{{ route('admin.reviews.index') }}" class="block">Reviews</a>
            <a href="{{ route('admin.comments.index') }}" class="block">Comments</a>
            <a href="{{ route('admin.reactions.index') }}" class="block">Reactions</a>
        </nav>
    </aside>
    <main class="flex-1 p-6">
        {{ $slot }}
    </main>
</div>
</body>
</html>
