<x-admin-layout>
    <h1 class="text-xl font-bold mb-4">Edit User</h1>
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block">Role</label>
            <select name="role" class="border rounded w-full">
                <option value="user" @selected($user->role==='user')>user</option>
                <option value="moderator" @selected($user->role==='moderator')>moderator</option>
                <option value="admin" @selected($user->role==='admin')>admin</option>
            </select>
        </div>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded" type="submit">Save</button>
    </form>
</x-admin-layout>
