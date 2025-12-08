<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('urutan')->get();
        return view('admin.testimonial.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonial.form', [
            'testimonial' => new Testimonial(),
            'action' => route('admin.testimonial.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Testimonial::create($data);
        return redirect()->route('admin.testimonial.index')->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonial.form', [
            'testimonial' => $testimonial,
            'action' => route('admin.testimonial.update', $testimonial->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $this->validateData($request);
        $testimonial->update($data);
        return redirect()->route('admin.testimonial.index')->with('success', 'Testimoni diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonial.index')->with('success', 'Testimoni dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'pesan' => 'nullable|string',
            'video_url' => 'nullable|url',
            'urutan' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);
    }
}
