<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfographicCard;
use App\Models\InfographicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;

class InfographicCardController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index(Request $request)
    {
        $statusOptions = InfographicCard::statuses();
        $statusFilter = $request->input('status');

        $query = InfographicCard::with('year')->orderBy('infographic_year_id')->orderBy('urutan');
        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }

        $cards = $query->get();
        return view('admin.infographic.card.index', [
            'cards' => $cards,
            'statusOptions' => $statusOptions,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function create()
    {
        return view('admin.infographic.card.form', [
            'card' => new InfographicCard(),
            'years' => InfographicYear::orderBy('tahun', 'desc')->pluck('tahun', 'id'),
            'action' => route('admin.infographic-card.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $this->applyWorkflow($request, $data);
        $card = InfographicCard::create($data);

        $this->logger->log(
            $request->user(),
            'infographic.card.created',
            "Card infografis '{$card->title}' ditambahkan",
            $card
        );
        return redirect()->route('admin.infographic-card.index')->with('success', 'Card ditambahkan.');
    }

    public function edit(InfographicCard $infographic_card)
    {
        return view('admin.infographic.card.form', [
            'card' => $infographic_card,
            'years' => InfographicYear::orderBy('tahun', 'desc')->pluck('tahun', 'id'),
            'action' => route('admin.infographic-card.update', $infographic_card->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, InfographicCard $infographic_card)
    {
        $data = $this->validateData($request);
        $this->applyWorkflow($request, $data, $infographic_card);
        $infographic_card->update($data);

        $this->logger->log(
            $request->user(),
            'infographic.card.updated',
            "Card infografis '{$infographic_card->title}' diperbarui",
            $infographic_card
        );
        return redirect()->route('admin.infographic-card.index')->with('success', 'Card diperbarui.');
    }

    public function destroy(InfographicCard $infographic_card)
    {
        if ($infographic_card->image ?? false) {
            $old = str_replace('/storage/', '', $infographic_card->image);
            Storage::disk('public')->delete($old);
        }
        $infographic_card->delete();

        $this->logger->log(
            request()->user(),
            'infographic.card.deleted',
            "Card infografis '{$infographic_card->title}' dihapus",
            $infographic_card
        );
        return redirect()->route('admin.infographic-card.index')->with('success', 'Card dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'infographic_year_id' => 'required|exists:infographic_years,id',
            'title' => 'required|string|max:255',
            'entries' => 'nullable|string',
            'urutan' => 'nullable|integer|min:0',
            'status' => 'nullable|in:' . implode(',', array_keys(InfographicCard::statuses())),
        ]);
        $entries = [];
        if (!empty($data['entries'])) {
            $entries = array_values(array_filter(array_map('trim', preg_split("/(\r?\n)+/", $data['entries']))));
        }
        $data['entries'] = $entries;
        $data['urutan'] = $data['urutan'] ?? 0;
        return $data;
    }

    private function applyWorkflow(Request $request, array &$data, ?InfographicCard $card = null): void
    {
        $defaultStatus = $request->user()->hasPermission('approve-content') ? 'published' : 'draft';
        $currentStatus = $card ? $card->status : $defaultStatus;
        $requestedStatus = $data['status'] ?? $currentStatus;

        if (! $request->user()->hasPermission('approve-content')) {
            $requestedStatus = $requestedStatus === 'published' ? 'pending' : $requestedStatus;
        }

        $data['status'] = $requestedStatus ?: $currentStatus;

        if ($data['status'] === 'published') {
            $data['approved_by'] = $request->user()->id;
            $data['approved_at'] = now();
            $data['published_at'] = $card?->published_at ?? now();
        } else {
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }
    }
}
