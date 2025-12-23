<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfographicEmbed;
use App\Models\InfographicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;

class InfographicEmbedController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index(Request $request)
    {
        $statusOptions = InfographicEmbed::statuses();
        $statusFilter = $request->input('status');

        $query = InfographicEmbed::with('year')->orderBy('infographic_year_id')->orderBy('urutan');
        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }

        $embeds = $query->get();
        return view('admin.infographic.embed.index', [
            'embeds' => $embeds,
            'statusOptions' => $statusOptions,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function create()
    {
        return view('admin.infographic.embed.form', [
            'embed' => new InfographicEmbed(),
            'years' => InfographicYear::orderBy('tahun', 'desc')->pluck('tahun', 'id'),
            'action' => route('admin.infographic-embed.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $this->applyWorkflow($request, $data);
        $embed = InfographicEmbed::create($data);

        $this->logger->log(
            $request->user(),
            'infographic.embed.created',
            "Embed infografis '{$embed->title}' ditambahkan",
            $embed
        );
        return redirect()->route('admin.infographic-embed.index')->with('success', 'Embed ditambahkan.');
    }

    public function edit(InfographicEmbed $infographic_embed)
    {
        return view('admin.infographic.embed.form', [
            'embed' => $infographic_embed,
            'years' => InfographicYear::orderBy('tahun', 'desc')->pluck('tahun', 'id'),
            'action' => route('admin.infographic-embed.update', $infographic_embed->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, InfographicEmbed $infographic_embed)
    {
        $data = $this->validateData($request);
        $this->applyWorkflow($request, $data, $infographic_embed);
        $infographic_embed->update($data);

        $this->logger->log(
            $request->user(),
            'infographic.embed.updated',
            "Embed infografis '{$infographic_embed->title}' diperbarui",
            $infographic_embed
        );
        return redirect()->route('admin.infographic-embed.index')->with('success', 'Embed diperbarui.');
    }

    public function destroy(InfographicEmbed $infographic_embed)
    {
        $infographic_embed->delete();

        $this->logger->log(
            request()->user(),
            'infographic.embed.deleted',
            "Embed infografis '{$infographic_embed->title}' dihapus",
            $infographic_embed
        );
        return redirect()->route('admin.infographic-embed.index')->with('success', 'Embed dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'infographic_year_id' => 'required|exists:infographic_years,id',
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'height' => 'nullable|integer|min:200|max:2000',
            'urutan' => 'nullable|integer|min:0',
            'status' => 'nullable|in:' . implode(',', array_keys(InfographicEmbed::statuses())),
        ]);
        $data['height'] = $data['height'] ?? 600;
        $data['urutan'] = $data['urutan'] ?? 0;
        return $data;
    }

    private function applyWorkflow(Request $request, array &$data, ?InfographicEmbed $embed = null): void
    {
        $defaultStatus = $request->user()->hasPermission('approve-content') ? 'published' : 'draft';
        $currentStatus = $embed ? $embed->status : $defaultStatus;
        $requestedStatus = $data['status'] ?? $currentStatus;

        if (! $request->user()->hasPermission('approve-content')) {
            $requestedStatus = $requestedStatus === 'published' ? 'pending' : $requestedStatus;
        }

        $data['status'] = $requestedStatus ?: $currentStatus;

        if ($data['status'] === 'published') {
            $data['approved_by'] = $request->user()->id;
            $data['approved_at'] = now();
            $data['published_at'] = $embed?->published_at ?? now();
        } else {
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }
    }
}
