<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactChannel;
use Illuminate\Http\Request;

class ContactChannelController extends Controller
{
    public function index()
    {
        $channels = ContactChannel::orderBy('urutan')->get();
        return view('admin.contact.channels.index', compact('channels'));
    }

    public function create()
    {
        $channel = new ContactChannel(['is_active' => true]);
        return view('admin.contact.channels.form', compact('channel'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        ContactChannel::create($data);

        return redirect()->route('admin.contact-channel.index')->with('success', 'Channel kontak ditambahkan.');
    }

    public function edit(ContactChannel $contact_channel)
    {
        return view('admin.contact.channels.form', ['channel' => $contact_channel]);
    }

    public function update(Request $request, ContactChannel $contact_channel)
    {
        $data = $this->validateData($request);
        $contact_channel->update($data);

        return redirect()->route('admin.contact-channel.index')->with('success', 'Channel kontak diperbarui.');
    }

    public function destroy(ContactChannel $contact_channel)
    {
        $contact_channel->delete();

        return redirect()->route('admin.contact-channel.index')->with('success', 'Channel kontak dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'link' => 'nullable|string|max:255',
            'label' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
