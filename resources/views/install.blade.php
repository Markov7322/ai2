<x-guest-layout>
    <form method="POST" action="/install">
        @csrf
        <div>
            <x-input-label for="db_connection" value="DB Connection" />
            <x-text-input id="db_connection" name="db_connection" class="block mt-1 w-full" value="mysql" required />
            <x-input-error :messages="$errors->get('db_connection')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="db_host" value="DB Host" />
            <x-text-input id="db_host" name="db_host" class="block mt-1 w-full" value="127.0.0.1" />
            <x-input-error :messages="$errors->get('db_host')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="db_port" value="DB Port" />
            <x-text-input id="db_port" name="db_port" class="block mt-1 w-full" value="3306" />
            <x-input-error :messages="$errors->get('db_port')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="db_database" value="DB Database" />
            <x-text-input id="db_database" name="db_database" class="block mt-1 w-full" required />
            <x-input-error :messages="$errors->get('db_database')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="db_username" value="DB Username" />
            <x-text-input id="db_username" name="db_username" class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('db_username')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="db_password" value="DB Password" />
            <x-text-input id="db_password" name="db_password" type="password" class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('db_password')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="admin_email" value="Admin Email" />
            <x-text-input id="admin_email" name="admin_email" type="email" class="block mt-1 w-full" required />
            <x-input-error :messages="$errors->get('admin_email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="admin_password" value="Admin Password" />
            <x-text-input id="admin_password" name="admin_password" type="password" class="block mt-1 w-full" required />
            <x-input-error :messages="$errors->get('admin_password')" class="mt-2" />
        </div>
        <div class="mt-6 flex justify-end">
            <x-primary-button>Install</x-primary-button>
        </div>
    </form>
</x-guest-layout>
