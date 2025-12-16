<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfographicMetric;
use App\Models\InfographicYear;
use Illuminate\Http\Request;

class InfographicMetricController extends Controller
{
    public function index()
    {
        $metrics = InfographicMetric::with('year')->orderBy('infographic_year_id')->orderBy('urutan')->get();
        return view('admin.infographic.metric.index', compact('metrics'));
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
        InfographicMetric::create($data);
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
        $infographic_metric->update($data);
        return redirect()->route('admin.infographic-metric.index')->with('success', 'Metric diperbarui.');
    }

    public function destroy(InfographicMetric $infographic_metric)
    {
        $infographic_metric->delete();
        return redirect()->route('admin.infographic-metric.index')->with('success', 'Metric dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'infographic_year_id' => 'required|exists:infographic_years,id',
            'label' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'urutan' => 'nullable|integer|min:0',
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;
        return $data;
    }
}
