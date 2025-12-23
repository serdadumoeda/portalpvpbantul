<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;

class PartnerController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index(Request $request)
    {
        $statusOptions = Partner::statuses();
        $statusFilter = $request->input('status');

        $query = Partner::orderBy('urutan');
        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }

        $partners = $query->get();
        return view('admin.partner.index', [
            'partners' => $partners,
            'statusOptions' => $statusOptions,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function create()
    {
        return view('admin.partner.form', [
            'partner' => new Partner(),
            'action' => route('admin.partner.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('partners', 'public');
            $data['logo'] = '/storage/' . $path;
        }
        $this->applyWorkflow($request, $data);
        $partner = Partner::create($data);

        $this->logger->log(
            $request->user(),
            'partner.created',
            "Partner '{$partner->nama}' ditambahkan",
            $partner
        );
        return redirect()->route('admin.partner.index')->with('success', 'Partner berhasil ditambahkan.');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partner.form', [
            'partner' => $partner,
            'action' => route('admin.partner.update', $partner->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $this->validateData($request);
        if ($request->hasFile('logo')) {
            if ($partner->logo && Storage::exists(str_replace('/storage/', 'public/', $partner->logo))) {
                Storage::delete(str_replace('/storage/', 'public/', $partner->logo));
            }
            $path = $request->file('logo')->store('partners', 'public');
            $data['logo'] = '/storage/' . $path;
        }
        $this->applyWorkflow($request, $data, $partner);
        $partner->update($data);

        $this->logger->log(
            $request->user(),
            'partner.updated',
            "Partner '{$partner->nama}' diperbarui",
            $partner
        );
        return redirect()->route('admin.partner.index')->with('success', 'Partner berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo && Storage::exists(str_replace('/storage/', 'public/', $partner->logo))) {
            Storage::delete(str_replace('/storage/', 'public/', $partner->logo));
        }
        $partner->delete();

        $this->logger->log(
            request()->user(),
            'partner.deleted',
            "Partner '{$partner->nama}' dihapus",
            $partner
        );
        return redirect()->route('admin.partner.index')->with('success', 'Partner berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'tautan' => 'nullable|url|max:255',
            'urutan' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'nullable|in:' . implode(',', array_keys(\App\Models\Partner::statuses())),
        ]);

        $data['urutan'] = $data['urutan'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }

    private function applyWorkflow(Request $request, array &$data, ?Partner $partner = null): void
    {
        $currentStatus = $partner ? $partner->status : ($request->user()->hasPermission('approve-content') ? 'published' : 'draft');
        $requestedStatus = $data['status'] ?? $currentStatus;

        if (! $request->user()->hasPermission('approve-content') && $requestedStatus === 'published') {
            $requestedStatus = 'pending';
        }

        $data['status'] = $requestedStatus ?: $currentStatus;

        if ($data['status'] === 'published') {
            $data['approved_by'] = $request->user()->id;
            $data['approved_at'] = now();
            $data['published_at'] = $partner?->published_at ?? now();
        } else {
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }
    }
}
