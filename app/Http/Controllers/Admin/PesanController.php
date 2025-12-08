<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PesanController extends Controller
{
    public function index()
    {
        $pesan = \App\Models\Pesan::latest()->paginate(10);
        return view('admin.pesan.index', compact('pesan'));
    }
}
