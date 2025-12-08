<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqCategoryController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::orderBy('urutan')->get();
        return view('admin.faq.categories.index', compact('categories'));
    }

    public function create()
    {
        $category = new FaqCategory(['is_active' => true]);
        return view('admin.faq.categories.form', compact('category'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        FaqCategory::create($data);

        return redirect()->route('admin.faq-category.index')->with('success', 'Kategori FAQ ditambahkan.');
    }

    public function edit(FaqCategory $faq_category)
    {
        return view('admin.faq.categories.form', ['category' => $faq_category]);
    }

    public function update(Request $request, FaqCategory $faq_category)
    {
        $data = $this->validateData($request);
        $faq_category->update($data);

        return redirect()->route('admin.faq-category.index')->with('success', 'Kategori FAQ diperbarui.');
    }

    public function destroy(FaqCategory $faq_category)
    {
        $faq_category->delete();

        return redirect()->route('admin.faq-category.index')->with('success', 'Kategori FAQ dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
