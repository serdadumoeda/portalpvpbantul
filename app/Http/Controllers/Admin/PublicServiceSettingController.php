<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicServiceSetting;
use Illuminate\Http\Request;

class PublicServiceSettingController extends Controller
{
    public function edit()
    {
        $setting = PublicServiceSetting::first() ?? new PublicServiceSetting();
        return view('admin.pelayanan.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string',
            'hero_button_text' => 'nullable|string|max:150',
            'hero_button_link' => 'nullable|string|max:255',
            'hero_image' => 'nullable|image|max:2048',
            'intro_title' => 'nullable|string|max:255',
            'intro_description' => 'nullable|string',
            'intro_content' => 'nullable|string',
            'regulation_title' => 'nullable|string|max:255',
            'regulation_items' => 'nullable|string',
            'policy_title' => 'nullable|string|max:255',
            'policy_subtitle' => 'nullable|string|max:255',
            'policy_description' => 'nullable|string',
            'policy_signature' => 'nullable|string|max:255',
            'policy_position' => 'nullable|string|max:255',
            'policy_image' => 'nullable|image|max:2048',
            'standard_title' => 'nullable|string|max:255',
            'standard_description' => 'nullable|string',
            'standard_document_title' => 'nullable|string|max:255',
            'standard_document_description' => 'nullable|string',
            'standard_document_file' => 'nullable|file|max:4096',
            'standard_document_badge' => 'nullable|string|max:100',
            'flow_section_title' => 'nullable|string|max:255',
            'flow_section_description' => 'nullable|string',
            'cta_title' => 'nullable|string|max:255',
            'cta_description' => 'nullable|string',
            'cta_primary_text' => 'nullable|string|max:100',
            'cta_primary_link' => 'nullable|string|max:255',
            'cta_secondary_text' => 'nullable|string|max:100',
            'cta_secondary_link' => 'nullable|string|max:255',
        ]);

        $setting = PublicServiceSetting::first() ?? new PublicServiceSetting();

        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = '/storage/' . $request->file('hero_image')->store('pelayanan', 'public');
        }

        if ($request->hasFile('policy_image')) {
            $data['policy_image'] = '/storage/' . $request->file('policy_image')->store('pelayanan', 'public');
        }

        if ($request->hasFile('standard_document_file')) {
            $data['standard_document_file'] = '/storage/' . $request->file('standard_document_file')->store('pelayanan', 'public');
        }

        $setting->fill($data)->save();

        return redirect()->route('admin.public-service.settings')->with('success', 'Pengaturan pelayanan publik diperbarui.');
    }
}
