<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\ActivityLogger;

class PublicationCategoryController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index()
    {
        $categories = PublicationCategory::orderBy('urutan')->get();
        return view('admin.publication.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.publication.category.form', [
            'category' => new PublicationCategory(),
            'action' => route('admin.publication-category.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $category = PublicationCategory::create($data);

        $this->logger->log(
            $request->user(),
            'publication_category.created',
            "Kategori publikasi '{$category->name}' ditambahkan",
            $category
        );
        return redirect()->route('admin.publication-category.index')->with('success', 'Kategori publikasi ditambahkan.');
    }

    public function edit(PublicationCategory $publication_category)
    {
        return view('admin.publication.category.form', [
            'category' => $publication_category,
            'action' => route('admin.publication-category.update', $publication_category->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, PublicationCategory $publication_category)
    {
        $data = $this->validateData($request, $publication_category->id);
        $publication_category->update($data);

        $this->logger->log(
            $request->user(),
            'publication_category.updated',
            "Kategori publikasi '{$publication_category->name}' diperbarui",
            $publication_category
        );
        return redirect()->route('admin.publication-category.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(PublicationCategory $publication_category)
    {
        $this->logger->log(
            request()->user(),
            'publication_category.deleted',
            "Kategori publikasi '{$publication_category->name}' dihapus",
            $publication_category
        );
        $publication_category->delete();
        return redirect()->route('admin.publication-category.index')->with('success', 'Kategori dihapus.');
    }

    private function validateData(Request $request, $id = null): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:publication_categories,slug,' . $id,
            'layout' => 'required|string|max:50',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'columns' => 'nullable|integer|min:1|max:6',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['columns'] = $data['columns'] ?? 4;
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }
}
