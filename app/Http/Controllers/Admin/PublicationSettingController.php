<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicationSetting;
use Illuminate\Http\Request;

class PublicationSettingController extends Controller
{
    public function edit()
    {
        $setting = PublicationSetting::first() ?? new PublicationSetting();
        return view('admin.publication.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string',
            'hero_button_text' => 'nullable|string|max:100',
            'hero_button_link' => 'nullable|string|max:255',
            'hero_image' => 'nullable|image|max:2048',
            'intro_title' => 'nullable|string|max:255',
            'intro_description' => 'nullable|string',
            'alumni_title' => 'nullable|string|max:255',
            'alumni_description' => 'nullable|string',
            'alumni_video_url' => 'nullable|string|max:255',
            'downloads_title' => 'nullable|string|max:255',
            'downloads_description' => 'nullable|string',
        ]);

        $setting = PublicationSetting::first() ?? new PublicationSetting();
        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = '/storage/' . $request->file('hero_image')->store('publications', 'public');
        }
        $setting->fill($data)->save();

        return redirect()->route('admin.publication.settings')->with('success', 'Pengaturan publikasi disimpan.');
    }
}
