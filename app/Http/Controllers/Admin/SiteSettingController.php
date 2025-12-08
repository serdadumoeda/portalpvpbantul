<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function edit()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('admin.settings.site', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'cta_title' => 'nullable|string',
            'cta_subtitle' => 'nullable|string',
            'cta_button_1_text' => 'nullable|string',
            'cta_button_1_link' => 'nullable|string',
            'cta_button_2_text' => 'nullable|string',
            'cta_button_2_link' => 'nullable|string',
            'footer_address' => 'nullable|string',
            'footer_email' => 'nullable|string',
            'footer_phone' => 'nullable|string',
            'footer_phone_alt' => 'nullable|string',
            'footer_instagram' => 'nullable|string',
            'footer_facebook' => 'nullable|string',
            'footer_twitter' => 'nullable|string',
            'footer_youtube' => 'nullable|string',
            'footer_sp4n' => 'nullable|string',
            'footer_operasional' => 'nullable|string',
            'footer_embed_map' => 'nullable|string',
        ]);

        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.settings.site')->with('success', 'Pengaturan situs disimpan.');
    }
}
