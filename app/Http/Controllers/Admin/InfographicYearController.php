<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfographicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;

class InfographicYearController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index(Request $request)
    {
        $statusOptions = InfographicYear::statuses();
        $statusFilter = $request->input('status');

        $query = InfographicYear::orderBy('urutan');
        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }

        $years = $query->get();
        return view('admin.infographic.year.index', [
            'years' => $years,
            'statusOptions' => $statusOptions,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function create()
    {
        return view('admin.infographic.year.form', [
            'year' => new InfographicYear(),
            'action' => route('admin.infographic-year.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = '/storage/' . $request->file('hero_image')->store('infographics', 'public');
        }
        $this->applyWorkflow($request, $data);
        $year = InfographicYear::create($data);

        $this->logger->log(
            $request->user(),
            'infographic.year.created',
            "Infografis tahun {$year->tahun} ditambahkan",
            $year
        );
        return redirect()->route('admin.infographic-year.index')->with('success', 'Data infografis ditambahkan.');
    }

    public function edit(InfographicYear $infographic_year)
    {
        return view('admin.infographic.year.form', [
            'year' => $infographic_year,
            'action' => route('admin.infographic-year.update', $infographic_year->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, InfographicYear $infographic_year)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('hero_image')) {
            if ($infographic_year->hero_image) {
                $old = str_replace('/storage/', '', $infographic_year->hero_image);
                Storage::disk('public')->delete($old);
            }
            $data['hero_image'] = '/storage/' . $request->file('hero_image')->store('infographics', 'public');
        }
        $this->applyWorkflow($request, $data, $infographic_year);
        $infographic_year->update($data);

        $this->logger->log(
            $request->user(),
            'infographic.year.updated',
            "Infografis tahun {$infographic_year->tahun} diperbarui",
            $infographic_year
        );
        return redirect()->route('admin.infographic-year.index')->with('success', 'Data infografis diperbarui.');
    }

    public function destroy(InfographicYear $infographic_year)
    {
        if ($infographic_year->hero_image) {
            $old = str_replace('/storage/', '', $infographic_year->hero_image);
            Storage::disk('public')->delete($old);
        }
        $infographic_year->delete();

        $this->logger->log(
            request()->user(),
            'infographic.year.deleted',
            "Infografis tahun {$infographic_year->tahun} dihapus",
            $infographic_year
        );
        return redirect()->route('admin.infographic-year.index')->with('success', 'Data infografis dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'tahun' => 'required|digits:4',
            'title' => 'nullable|string|max:255',
            'headline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'hero_button_text' => 'nullable|string|max:100',
            'hero_button_link' => 'nullable|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|in:' . implode(',', array_keys(InfographicYear::statuses())),
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');
        return $data;
    }

    private function applyWorkflow(Request $request, array &$data, ?InfographicYear $year = null): void
    {
        $defaultStatus = $request->user()->hasPermission('approve-content') ? 'published' : 'draft';
        $currentStatus = $year ? $year->status : $defaultStatus;
        $requestedStatus = $data['status'] ?? $currentStatus;

        if (! $request->user()->hasPermission('approve-content')) {
            $requestedStatus = $requestedStatus === 'published' ? 'pending' : $requestedStatus;
        }

        $data['status'] = $requestedStatus ?: $currentStatus;

        if ($data['status'] === 'published') {
            $data['approved_by'] = $request->user()->id;
            $data['approved_at'] = now();
            $data['published_at'] = $year?->published_at ?? now();
        } else {
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }
    }
}
