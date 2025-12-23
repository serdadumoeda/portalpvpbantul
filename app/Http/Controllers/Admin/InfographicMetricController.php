<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfographicMetric;
use App\Models\InfographicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ActivityLogger;

class InfographicMetricController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index(Request $request)
    {
        $statusOptions = InfographicMetric::statuses();
        $statusFilter = $request->input('status');

        $query = InfographicMetric::with('year')->orderBy('infographic_year_id')->orderBy('urutan');
        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }

        $metrics = $query->get();
        return view('admin.infographic.metric.index', [
            'metrics' => $metrics,
            'statusOptions' => $statusOptions,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function create()
    {
        return view('admin.infographic.metric.form', [
            'metric' => new InfographicMetric(),
            'years' => InfographicYear::orderBy('tahun', 'desc')->pluck('tahun', 'id'),
            'action' => route('admin.infographic-metric.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $this->applyWorkflow($request, $data);
        $metric = InfographicMetric::create($data);

        $this->logger->log(
            $request->user(),
            'infographic.metric.created',
            "Metric infografis '{$metric->label}' ditambahkan",
            $metric
        );
        return redirect()->route('admin.infographic-metric.index')->with('success', 'Metric ditambahkan.');
    }

    public function edit(InfographicMetric $infographic_metric)
    {
        return view('admin.infographic.metric.form', [
            'metric' => $infographic_metric,
            'years' => InfographicYear::orderBy('tahun', 'desc')->pluck('tahun', 'id'),
            'action' => route('admin.infographic-metric.update', $infographic_metric->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, InfographicMetric $infographic_metric)
    {
        $data = $this->validateData($request);
        $this->applyWorkflow($request, $data, $infographic_metric);
        $infographic_metric->update($data);

        $this->logger->log(
            $request->user(),
            'infographic.metric.updated',
            "Metric infografis '{$infographic_metric->label}' diperbarui",
            $infographic_metric
        );
        return redirect()->route('admin.infographic-metric.index')->with('success', 'Metric diperbarui.');
    }

    public function destroy(InfographicMetric $infographic_metric)
    {
        $infographic_metric->delete();

        $this->logger->log(
            request()->user(),
            'infographic.metric.deleted',
            "Metric infografis '{$infographic_metric->label}' dihapus",
            $infographic_metric
        );
        return redirect()->route('admin.infographic-metric.index')->with('success', 'Metric dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'infographic_year_id' => 'required|exists:infographic_years,id',
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'urutan' => 'nullable|integer|min:0',
            'status' => 'nullable|in:' . implode(',', array_keys(InfographicMetric::statuses())),
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;
        return $data;
    }

    private function applyWorkflow(Request $request, array &$data, ?InfographicMetric $metric = null): void
    {
        $defaultStatus = $request->user()->hasPermission('approve-content') ? 'published' : 'draft';
        $currentStatus = $metric ? $metric->status : $defaultStatus;
        $requestedStatus = $data['status'] ?? $currentStatus;

        if (! $request->user()->hasPermission('approve-content')) {
            $requestedStatus = $requestedStatus === 'published' ? 'pending' : $requestedStatus;
        }

        $data['status'] = $requestedStatus ?: $currentStatus;

        if ($data['status'] === 'published') {
            $data['approved_by'] = $request->user()->id;
            $data['approved_at'] = now();
            $data['published_at'] = $metric?->published_at ?? now();
        } else {
            $data['approved_by'] = null;
            $data['approved_at'] = null;
        }
    }
}
