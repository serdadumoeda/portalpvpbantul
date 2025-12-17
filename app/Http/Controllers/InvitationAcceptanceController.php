<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationAcceptanceController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function show(string $token)
    {
        $invitation = Invitation::findByToken($token);

        if (! $invitation || ! $invitation->isValid()) {
            return redirect()->route('login')->with('error', 'Tautan undangan tidak berlaku.');
        }

        return view('auth.invite', [
            'invitation' => $invitation,
            'token' => $token,
        ]);
    }

    public function accept(Request $request, string $token)
    {
        $invitation = Invitation::findByToken($token);

        if (! $invitation || ! $invitation->isValid()) {
            return redirect()->route('login')->with('error', 'Tautan undangan tidak berlaku.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $invitation->email,
            'password' => $data['password'],
        ]);

        if ($invitation->role_id) {
            $user->syncRoles([$invitation->role_id]);
        }

        $invitation->used_at = now();
        $invitation->used_by_id = $user->id;
        $invitation->save();

        $this->logger->log(
            $user,
            'invitation.accepted',
            'Pengguna menerima undangan',
            $invitation,
            ['email' => $user->email]
        );

        Auth::login($user);

        $defaultRedirect = $user->hasPermission('access-admin')
            ? route('admin.dashboard')
            : route('alumni.forum.index');

        return redirect()->intended($defaultRedirect)->with('success', 'Akun berhasil dibuat.');
    }
}
