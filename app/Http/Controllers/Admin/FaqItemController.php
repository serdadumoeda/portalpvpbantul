<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\FaqItem;
use Illuminate\Http\Request;

class FaqItemController extends Controller
{
    public function index()
    {
        $items = FaqItem::with('category')->orderBy('faq_category_id')->orderBy('urutan')->get();
        return view('admin.faq.items.index', compact('items'));
    }

    public function create()
    {
        $item = new FaqItem(['is_active' => true]);
        $categories = FaqCategory::orderBy('title')->pluck('title', 'id');
        return view('admin.faq.items.form', compact('item', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        FaqItem::create($data);

        return redirect()->route('admin.faq-item.index')->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit(FaqItem $faq_item)
    {
        $categories = FaqCategory::orderBy('title')->pluck('title', 'id');
        return view('admin.faq.items.form', ['item' => $faq_item, 'categories' => $categories]);
    }

    public function update(Request $request, FaqItem $faq_item)
    {
        $data = $this->validateData($request);
        $faq_item->update($data);

        return redirect()->route('admin.faq-item.index')->with('success', 'FAQ diperbarui.');
    }

    public function destroy(FaqItem $faq_item)
    {
        $faq_item->delete();

        return redirect()->route('admin.faq-item.index')->with('success', 'FAQ dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'faq_category_id' => 'required|exists:faq_categories,id',
            'question' => 'required|string|max:255',
            'answer' => 'nullable|string',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);

        return $data;
    }
}
