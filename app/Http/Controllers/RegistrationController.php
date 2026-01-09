<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function show()
    {
        return redirect()->route('login')->with('error', 'Pendaftaran manual dinonaktifkan. Gunakan akun SIAP Kerja.');
    }

    public function register(Request $request)
    {
        return redirect()->route('login')->with('error', 'Pendaftaran manual dinonaktifkan. Gunakan akun SIAP Kerja.');
    }
}
