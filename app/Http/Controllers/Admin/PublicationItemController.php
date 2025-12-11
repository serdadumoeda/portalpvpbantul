<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicationItem;
use App\Models\PublicationCategory;
use Illuminate\Http\Request;

class PublicationItemController extends Controller
{
    public function index()
    {
        $items = PublicationItem::with('category')->orderBy('publication_category_id')->orderBy('urutan')->get();
        return view('admin.publication.item.index', compact('items'));
    }

    public function create()
    {
        return view('admin.publication.item.form', [
            'item' => new PublicationItem(),
            'categories' => PublicationCategory::orderBy('urutan')->pluck('name', 'id'),
            'action' => route('admin.publication-item.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('image')) {
            $data['image'] = '/storage/' . $request->file('image')->store('publications', 'public');
        }
        PublicationItem::create($data);
        return redirect()->route('admin.publication-item.index')->with('success', 'Item publikasi ditambahkan.');
    }

    public function edit(PublicationItem $publication_item)
    {
        return view('admin.publication.item.form', [
            'item' => $publication_item,
            'categories' => PublicationCategory::orderBy('urutan')->pluck('name', 'id'),
            'action' => route('admin.publication-item.update', $publication_item->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, PublicationItem $publication_item)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('image')) {
            $data['image'] = '/storage/' . $request->file('image')->store('publications', 'public');
        }
        $publication_item->update($data);
        return redirect()->route('admin.publication-item.index')->with('success', 'Item publikasi diperbarui.');
    }

    public function destroy(PublicationItem $publication_item)
    {
        $publication_item->delete();
        return redirect()->route('admin.publication-item.index')->with('success', 'Item publikasi dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'publication_category_id' => 'required|exists:publication_categories,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'badge' => 'nullable|string|max:50',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|url|max:255',
            'extra' => 'nullable|string|max:2000',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $data['extra'] = $data['extra'] ? array_values(array_filter(array_map('trim', preg_split("/(\r?\n)+/", $data['extra'])))) : null;
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }
}
