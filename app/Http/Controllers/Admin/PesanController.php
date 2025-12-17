<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesan;
use Illuminate\Http\Request;

class PesanController extends Controller
{
    public function index()
    {
        $query = Pesan::latest();
        $statusFilter = request('status', 'all');
        $search = request('q');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%")
                    ->orWhere('subjek', 'ilike', "%{$search}%")
                    ->orWhere('pesan', 'ilike', "%{$search}%");
            });
        }

        if ($statusFilter === 'read') {
            $query->where('is_read', true);
        } elseif ($statusFilter === 'unread') {
            $query->where('is_read', false);
        }

        $pesan = $query->paginate(10)->withQueryString();
        $stats = [
            'total' => Pesan::count(),
            'read' => Pesan::where('is_read', true)->count(),
            'unread' => Pesan::where('is_read', false)->count(),
        ];

        $selectedMessage = null;
        if ($focus = request('focus')) {
            $selectedMessage = Pesan::find($focus);
        }

        return view('admin.pesan.index', compact('pesan', 'stats', 'statusFilter', 'selectedMessage'));
    }

    public function updateStatus(Request $request, Pesan $pesan)
    {
        $pesan->update([
            'is_read' => $request->boolean('is_read', true),
        ]);

        return redirect()->back()->with('status', $pesan->is_read ? 'Pesan ditandai sebagai sudah dibaca.' : 'Pesan ditandai belum dibaca.');
    }
}
