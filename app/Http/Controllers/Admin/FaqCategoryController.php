<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;

class FaqCategoryController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index()
    {
        $statusOptions = FaqCategory::statuses();
        $statusFilter = request('status');

        $query = FaqCategory::orderBy('urutan');
        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }

        $categories = $query->get();
        return view('admin.faq.categories.index', compact('categories', 'statusOptions', 'statusFilter'));
    }

    public function create()
    {
        $category = new FaqCategory(['is_active' => true]);
        return view('admin.faq.categories.form', compact('category'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $this->applyWorkflow($request, $data);
        $category = FaqCategory::create($data);

        $this->logger->log(
            $request->user(),
            'faq.category.created',
            "Kategori FAQ '{$category->title}' ditambahkan",
            $category
        );

        return redirect()->route('admin.faq-category.index')->with('success', 'Kategori FAQ ditambahkan.');
    }

    public function edit(FaqCategory $faq_category)
    {
        return view('admin.faq.categories.form', ['category' => $faq_category]);
    }

    public function update(Request $request, FaqCategory $faq_category)
    {
        $data = $this->validateData($request);
        $this->applyWorkflow($request, $data, $faq_category);
        $faq_category->update($data);

        $this->logger->log(
            $request->user(),
            'faq.category.updated',
            "Kategori FAQ '{$faq_category->title}' diperbarui",
            $faq_category
        );

        return redirect()->route('admin.faq-category.index')->with('success', 'Kategori FAQ diperbarui.');
    }

    public function destroy(FaqCategory $faq_category)
    {
        $this->logger->log(
            request()->user(),
            'faq.category.deleted',
            "Kategori FAQ '{$faq_category->title}' dihapus",
            $faq_category
        );
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
            'status' => 'nullable|in:' . implode(',', array_keys(FaqCategory::statuses())),
        ]);

        $data['urutan'] = $data['urutan'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function applyWorkflow(Request $request, array &$data, ?FaqCategory $category = null): void
    {
        $defaultStatus = $request->user()->hasPermission('approve-content') ? 'published' : 'draft';
        $currentStatus = $category ? $category->status : $defaultStatus;
        $requestedStatus = $data['status'] ?? $currentStatus;

        if (! $request->user()->hasPermission('approve-content')) {
            $requestedStatus = $requestedStatus === 'published' ? 'pending' : $requestedStatus;
        }

        $data['status'] = $requestedStatus ?: $currentStatus;

        if ($data['status'] === 'published') {
            $data['approved_by'] = $request->user()->id;
            $data['approved_at'] = now();
            $data['published_at'] = $category?->published_at ?? now();
        } else {
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }
    }
}
