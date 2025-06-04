<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\User;

class InstallController extends Controller
{
    public function show()
    {
        return view('install');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'db_connection' => 'required|string',
            'db_host' => 'nullable|string',
            'db_port' => 'nullable|string',
            'db_database' => 'required|string',
            'db_username' => 'nullable|string',
            'db_password' => 'nullable|string',
            'admin_email' => 'required|email',
            'admin_password' => 'required|string',
        ]);

        if (!File::exists(base_path('.env'))) {
            File::copy(base_path('.env.example'), base_path('.env'));
        }

        $env = File::get(base_path('.env'));
        $env = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY='.Str::random(32), $env);
        $env = preg_replace('/^DB_CONNECTION=.*$/m', 'DB_CONNECTION='.$data['db_connection'], $env);
        $env = preg_replace('/^DB_HOST=.*$/m', 'DB_HOST='.$data['db_host'], $env);
        $env = preg_replace('/^DB_PORT=.*$/m', 'DB_PORT='.$data['db_port'], $env);
        $env = preg_replace('/^DB_DATABASE=.*$/m', 'DB_DATABASE='.$data['db_database'], $env);
        $env = preg_replace('/^DB_USERNAME=.*$/m', 'DB_USERNAME='.$data['db_username'], $env);
        $env = preg_replace('/^DB_PASSWORD=.*$/m', 'DB_PASSWORD='.$data['db_password'], $env);
        if (str_contains($env, 'APP_INSTALLED') === false) {
            $env .= "\nAPP_INSTALLED=true\n";
        } else {
            $env = preg_replace('/^APP_INSTALLED=.*$/m', 'APP_INSTALLED=true', $env);
        }
        File::put(base_path('.env'), $env);

        Artisan::call('config:clear');
        Artisan::call('key:generate', ['--force' => true]);
        Artisan::call('migrate', ['--force' => true]);

        User::updateOrCreate(
            ['email' => $data['admin_email']],
            [
                'name' => 'Admin',
                'password' => Hash::make($data['admin_password']),
                'role' => 'admin',
            ]
        );

        return redirect('/')->with('status', 'Application installed');
    }
}
