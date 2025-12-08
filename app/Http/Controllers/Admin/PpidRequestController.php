<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpidRequest;

class PpidRequestController extends Controller
{
    public function index()
    {
        $requests = PpidRequest::latest()->paginate(20);
        return view('admin.ppid.requests.index', compact('requests'));
    }

    public function show(PpidRequest $ppid_request)
    {
        return view('admin.ppid.requests.show', compact('ppid_request'));
    }

    public function destroy(PpidRequest $ppid_request)
    {
        if ($ppid_request->tanda_tangan) {
            $path = public_path(ltrim($ppid_request->tanda_tangan, '/'));
            if (file_exists($path)) {
                @unlink($path);
            }
        }
        $ppid_request->delete();
        return redirect()->route('admin.ppid-request.index')->with('success', 'Permohonan dihapus.');
    }
}
