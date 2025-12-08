@extends('layouts.admin')

@section('content')
<div class="card shadow-sm border-0 col-lg-10">
    <div class="card-header bg-white">
        <h5 class="mb-0">Pengaturan CTA & Footer</h5>
    </div>
    <div class="card-body">
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        <form action="{{ route('admin.settings.site.update') }}" method="POST">
            @csrf
            @method('PUT')

            <h6 class="fw-bold mb-2">CTA "Tunggu Apalagi"</h6>
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="cta_title" class="form-control" value="{{ $settings['cta_title'] ?? '' }}" placeholder="Tunggu Apalagi? Yuk Langsung Daftar Kelasnya">
            </div>
            <div class="mb-3">
                <label class="form-label">Sub Judul</label>
                <textarea name="cta_subtitle" rows="2" class="form-control">{{ $settings['cta_subtitle'] ?? '' }}</textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Tombol 1 Teks</label>
                    <input type="text" name="cta_button_1_text" class="form-control" value="{{ $settings['cta_button_1_text'] ?? '' }}" placeholder="Daftar Pelatihan">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tombol 1 Link</label>
                    <input type="text" name="cta_button_1_link" class="form-control" value="{{ $settings['cta_button_1_link'] ?? '' }}" placeholder="https://...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tombol 2 Teks</label>
                    <input type="text" name="cta_button_2_text" class="form-control" value="{{ $settings['cta_button_2_text'] ?? '' }}" placeholder="Hubungi Kami">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tombol 2 Link</label>
                    <input type="text" name="cta_button_2_link" class="form-control" value="{{ $settings['cta_button_2_link'] ?? '' }}" placeholder="mailto:...">
                </div>
            </div>

            <hr>
            <h6 class="fw-bold mb-2">Footer / Kantor Pusat</h6>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="footer_address" rows="2" class="form-control">{{ $settings['footer_address'] ?? '' }}</textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="text" name="footer_email" class="form-control" value="{{ $settings['footer_email'] ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telp</label>
                    <input type="text" name="footer_phone" class="form-control" value="{{ $settings['footer_phone'] ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telp Alternatif</label>
                    <input type="text" name="footer_phone_alt" class="form-control" value="{{ $settings['footer_phone_alt'] ?? '' }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label">Jam Operasional</label>
                    <textarea name="footer_operasional" rows="2" class="form-control" placeholder="Jam Operasional: ...">{{ $settings['footer_operasional'] ?? '' }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Instagram</label>
                    <input type="text" name="footer_instagram" class="form-control" value="{{ $settings['footer_instagram'] ?? '' }}" placeholder="https://instagram.com/...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Facebook</label>
                    <input type="text" name="footer_facebook" class="form-control" value="{{ $settings['footer_facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Twitter / X</label>
                    <input type="text" name="footer_twitter" class="form-control" value="{{ $settings['footer_twitter'] ?? '' }}" placeholder="https://twitter.com/...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">YouTube</label>
                    <input type="text" name="footer_youtube" class="form-control" value="{{ $settings['footer_youtube'] ?? '' }}" placeholder="https://youtube.com/...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">SP4N / Lapor Link</label>
                    <input type="text" name="footer_sp4n" class="form-control" value="{{ $settings['footer_sp4n'] ?? '' }}">
                </div>
            </div>
            <div class="mb-3 mt-3">
                <label class="form-label">Embed Map (iframe)</label>
                <textarea name="footer_embed_map" rows="3" class="form-control" placeholder="<iframe ...>">{{ $settings['footer_embed_map'] ?? '' }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>
</div>
@endsection
