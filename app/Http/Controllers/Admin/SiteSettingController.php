<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            // Hero & beranda
            'home_hero_title' => 'nullable|string',
            'home_hero_subtitle' => 'nullable|string',
            'home_hero_image' => 'nullable|string',
            'home_hero_cta_primary_text' => 'nullable|string',
            'home_hero_cta_primary_link' => 'nullable|string',
            'home_hero_cta_secondary_text' => 'nullable|string',
            'home_hero_cta_secondary_link' => 'nullable|string',
            'home_benefit_title' => 'nullable|string',
            'home_benefit_image' => 'nullable|string',
            'home_program_title' => 'nullable|string',
            'home_program_subtitle' => 'nullable|string',
            'home_why_title' => 'nullable|string',
            'home_why_image' => 'nullable|string',
            'home_flow_title' => 'nullable|string',
            'home_flow_image' => 'nullable|string',
            'home_news_title' => 'nullable|string',
            'home_news_subtitle' => 'nullable|string',
            'home_testimonial_title' => 'nullable|string',
            'home_testimonial_subtitle' => 'nullable|string',
            'home_partner_title' => 'nullable|string',
            'home_partner_subtitle' => 'nullable|string',
            'home_instructor_title' => 'nullable|string',
            'home_instructor_subtitle' => 'nullable|string',
            'home_gallery_title' => 'nullable|string',
            'home_gallery_subtitle' => 'nullable|string',
            'home_hero_image_upload' => 'nullable|image|max:2048',
            'home_benefit_image_upload' => 'nullable|image|max:2048',
            'home_why_image_upload' => 'nullable|image|max:2048',
            'home_flow_image_upload' => 'nullable|image|max:2048',

            // SIAP Kerja / Skillhub API & SSO
            'siapkerja_client_id' => 'nullable|string',
            'siapkerja_client_secret' => 'nullable|string',
            'siapkerja_redirect' => 'nullable|string',
            'siapkerja_scope' => 'nullable|string',
            'siapkerja_api_base' => 'nullable|string',
            'siapkerja_token_url' => 'nullable|string',
            'siapkerja_profile_url' => 'nullable|string',
            'siapkerja_admin_client_id' => 'nullable|string',
            'siapkerja_admin_client_secret' => 'nullable|string',
            'siapkerja_admin_scope' => 'nullable|string',

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

        // Handle image uploads and override corresponding URL fields
        $uploadFields = [
            'home_hero_image_upload' => 'home_hero_image',
            'home_benefit_image_upload' => 'home_benefit_image',
            'home_why_image_upload' => 'home_why_image',
            'home_flow_image_upload' => 'home_flow_image',
        ];

        foreach ($uploadFields as $uploadKey => $settingKey) {
            if ($request->hasFile($uploadKey)) {
                $path = $request->file($uploadKey)->store('site-settings', 'public');
                $data[$settingKey] = Storage::url($path);
            }
            unset($data[$uploadKey]);
        }

        foreach ($data as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
            cache()->forget("site_setting:{$key}");
        }

        return redirect()->route('admin.settings.site')->with('success', 'Pengaturan situs disimpan.');
    }
}
