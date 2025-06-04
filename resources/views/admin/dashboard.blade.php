<x-admin-layout>
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    <div class="grid grid-cols-2 gap-4">
        <div class="p-4 bg-white rounded shadow">Reviews: {{ $stats['reviews'] }}</div>
        <div class="p-4 bg-white rounded shadow">Comments: {{ $stats['comments'] }}</div>
        <div class="p-4 bg-white rounded shadow">Users: {{ $stats['users'] }}</div>
        <div class="p-4 bg-white rounded shadow">Categories: {{ $stats['categories'] }}</div>
    </div>
</x-admin-layout>
