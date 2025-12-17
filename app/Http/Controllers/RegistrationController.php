<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $role = Role::where('name', 'alumni')->first();
        if ($role) {
            $user->syncRoles([$role->id]);
        }

        $this->logger->log(
            $user,
            'user.registered',
            'Alumni mendaftar secara mandiri',
            $user,
            ['role' => $role?->name ?? 'alumni']
        );

        Auth::login($user);

        return redirect()->route('alumni.forum.index')->with('success', 'Selamat datang! Akun Anda sudah aktif.');
    }
}
