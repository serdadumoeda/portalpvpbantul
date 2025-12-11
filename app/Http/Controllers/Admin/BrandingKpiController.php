<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandingKpiRequest;
use App\Models\BrandingKpiReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandingKpiController extends Controller
{
    public function index(Request $request)
    {
        $yearFilter = $request->input('year');

        $baseQuery = BrandingKpiReport::orderByDesc('year')->orderByDesc('month');
        if ($yearFilter) {
            $baseQuery->where('year', $yearFilter);
        }

        $reports = (clone $baseQuery)->paginate(12)->withQueryString();
        $latestReport = (clone $baseQuery)->first();

        $trendRecords = (clone $baseQuery)
            ->take(12)
            ->get()
            ->sortBy(fn ($report) => sprintf('%04d-%02d', $report->year, $report->month))
            ->values();

        $indicatorDefinitions = config('branding_kpi.indicators');
        $indicatorKeys = array_keys($indicatorDefinitions);

        $chartSeries = [];
        foreach ($indicatorKeys as $key) {
            $chartSeries[$key] = [];
            $chartSeries[$key . '_target'] = [];
        }

        $labels = [];
        foreach ($trendRecords as $record) {
            $labels[] = Carbon::create($record->year, $record->month, 1)->translatedFormat('M Y');

            foreach ($indicatorKeys as $key) {
                $chartSeries[$key][] = (float) ($record->{$key . '_current'} ?? 0);
                $chartSeries[$key . '_target'][] = $record->{$key . '_target'} ?? null;
            }
        }

        $palette = [
            'reach' => '#1d4ed8',
            'registrant' => '#0f766e',
            'rating' => '#ca8a04',
            'partner' => '#b91c1c',
        ];

        $chartData = [
            'labels' => $labels,
            'datasets' => [],
        ];

        foreach ($indicatorKeys as $key) {
            $color = $palette[$key] ?? '#2563eb';
            $chartData['datasets'][] = [
                'label' => $indicatorDefinitions[$key]['label'],
                'data' => $chartSeries[$key],
                'borderColor' => $color,
                'backgroundColor' => $color,
                'tension' => 0.3,
                'fill' => false,
                'pointRadius' => 4,
            ];

            $chartData['datasets'][] = [
                'label' => $indicatorDefinitions[$key]['label'] . ' Target',
                'data' => $chartSeries[$key . '_target'],
                'borderColor' => $color,
                'borderDash' => [6, 6],
                'backgroundColor' => $color,
                'tension' => 0.2,
                'fill' => false,
                'pointRadius' => 0,
            ];
        }

        $years = BrandingKpiReport::select('year')->distinct()->orderByDesc('year')->pluck('year');

        return view('admin.branding_kpi.index', compact('reports', 'latestReport', 'chartData', 'indicatorDefinitions', 'yearFilter', 'years'));
    }

    public function create()
    {
        $report = new BrandingKpiReport();
        $report->setRelation('lowInterestItems', collect());

        $lastReport = BrandingKpiReport::orderByDesc('year')->orderByDesc('month')->first();
        if ($lastReport) {
            $nextPeriod = Carbon::create($lastReport->year, $lastReport->month, 1)->addMonth();
            $report->month = $nextPeriod->month;
            $report->year = $nextPeriod->year;

            foreach (array_keys(config('branding_kpi.indicators')) as $indicatorKey) {
                $report->{$indicatorKey . '_previous'} = $lastReport->{$indicatorKey . '_current'};
                $report->{$indicatorKey . '_target'} = $lastReport->{$indicatorKey . '_target'};
            }
        }

        return view('admin.branding_kpi.form', [
            'report' => $report,
            'action' => route('admin.branding-kpi.store'),
            'method' => 'POST',
            'title' => 'Tambah Laporan Branding',
        ]);
    }

    public function store(BrandingKpiRequest $request)
    {
        DB::transaction(function () use ($request) {
            $report = BrandingKpiReport::create($request->validated());
            $this->syncLowInterestItems($report, $request);
        });

        return redirect()->route('admin.branding-kpi.index')->with('success', 'Laporan branding berhasil ditambahkan.');
    }

    public function edit(BrandingKpiReport $branding_kpi)
    {
        $branding_kpi->load('lowInterestItems');

        return view('admin.branding_kpi.form', [
            'report' => $branding_kpi,
            'action' => route('admin.branding-kpi.update', $branding_kpi),
            'method' => 'PUT',
            'title' => 'Edit Laporan Branding',
        ]);
    }

    public function update(BrandingKpiRequest $request, BrandingKpiReport $branding_kpi)
    {
        DB::transaction(function () use ($request, $branding_kpi) {
            $branding_kpi->update($request->validated());
            $this->syncLowInterestItems($branding_kpi, $request);
        });

        return redirect()->route('admin.branding-kpi.index')->with('success', 'Laporan branding diperbarui.');
    }

    public function destroy(BrandingKpiReport $branding_kpi)
    {
        $branding_kpi->delete();

        return redirect()->route('admin.branding-kpi.index')->with('success', 'Laporan branding dihapus.');
    }

    public function show(BrandingKpiReport $branding_kpi)
    {
        $branding_kpi->load('lowInterestItems');

        return view('admin.branding_kpi.show', compact('branding_kpi'));
    }

    public function download(BrandingKpiReport $branding_kpi)
    {
        $branding_kpi->load('lowInterestItems');

        $fileName = sprintf(
            'laporan-kpi-branding-%s-%s.html',
            $branding_kpi->year,
            str_pad($branding_kpi->month, 2, '0', STR_PAD_LEFT)
        );

        $content = view('admin.branding_kpi.report', compact('branding_kpi'))->render();

        return response()->streamDownload(fn () => print($content), $fileName, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    private function syncLowInterestItems(BrandingKpiReport $report, BrandingKpiRequest $request): void
    {
        $programs = $request->input('low_interest_programs', []);
        $issues = $request->input('low_interest_issues', []);
        $actions = $request->input('low_interest_actions', []);

        $report->lowInterestItems()->delete();

        foreach ($programs as $index => $programName) {
            $programName = trim((string) $programName);
            if ($programName === '') {
                continue;
            }

            $report->lowInterestItems()->create([
                'program_name' => $programName,
                'issue_description' => $issues[$index] ?? null,
                'action_plan' => $actions[$index] ?? null,
            ]);
        }
    }
}
