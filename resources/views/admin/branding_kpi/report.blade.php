<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; color: #1f2937; }
        h1, h2, h3 { margin-bottom: .5rem; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; font-size: .9rem; }
        th, td { border: 1px solid #cbd5f5; padding: .6rem; text-align: center; }
        th { background: #f1f5f9; }
        .section { margin-bottom: 2rem; }
        .meta-table td { text-align: left; border: none; padding: .3rem; }
        .signature { margin-top: 2rem; display: flex; gap: 2rem; }
        .signature div { flex: 1; text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan KPI Branding</h1>
    <p>Periode: {{ \Carbon\Carbon::create()->month($branding_kpi->month)->translatedFormat('F') }} {{ $branding_kpi->year }}</p>
    @if($branding_kpi->meeting_date)
        <p>Tanggal Rapat: {{ $branding_kpi->meeting_date->translatedFormat('d F Y') }}</p>
    @endif

    <div class="section">
        <table class="meta-table">
            <tr>
                <td><strong>Disusun oleh</strong></td>
                <td>{{ $branding_kpi->reported_by ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Disetujui oleh</strong></td>
                <td>{{ $branding_kpi->approved_by ?? 'Koordinator PVP Bantul' }}</td>
            </tr>
            <tr>
                <td><strong>Evidence</strong></td>
                <td>{{ $branding_kpi->evidence_link ?? '-' }}</td>
            </tr>
        </table>
    </div>

    @php $indicatorGroups = collect(config('branding_kpi.indicators'))->groupBy('category'); @endphp
    @foreach($indicatorGroups as $category => $indicators)
        <div class="section">
            <h3>{{ $category }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>Indikator</th>
                        <th>Bulan Lalu</th>
                        <th>Bulan Ini</th>
                        <th>Target</th>
                        <th>Selisih</th>
                        <th>Pencapaian</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($indicators as $key => $indicator)
                        <tr>
                            <td style="text-align:left">{{ $indicator['label'] }}</td>
                            <td>{{ $branding_kpi->{$key . '_previous'} ?? '-' }}</td>
                            <td>{{ $branding_kpi->{$key . '_current'} ?? '-' }}</td>
                            <td>{{ $branding_kpi->{$key . '_target'} ?? '-' }}</td>
                            <td>{{ $branding_kpi->{$key . '_difference'} ?? '-' }}</td>
                            <td>{{ $branding_kpi->{$key . '_achievement'} ? number_format($branding_kpi->{$key . '_achievement'}, 1) . '%' : '-' }}</td>
                            <td>{{ $branding_kpi->{$key . '_notes'} ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="section">
        <h3>Evaluasi Kejuruan Sepi Peminat</h3>
        @if($branding_kpi->lowInterestItems->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>Kejuruan</th>
                        <th>Masalah</th>
                        <th>Rencana Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($branding_kpi->lowInterestItems as $item)
                        <tr>
                            <td>{{ $item->program }}</td>
                            <td>{{ $item->issue }}</td>
                            <td>{{ $item->action_plan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Belum ada evaluasi kejuruan.</p>
        @endif
    </div>

    <div class="section">
        <h3>Catatan Rapat</h3>
        <p>{{ $branding_kpi->meeting_notes ?? 'Belum ada catatan.' }}</p>
    </div>

    <div class="signature">
        <div>
            <p>Disusun Oleh</p>
            <hr>
            <p>{{ $branding_kpi->reported_by ?? '-' }}</p>
        </div>
        <div>
            <p>Disetujui Oleh</p>
            <hr>
            <p>{{ $branding_kpi->approved_by ?? 'Koordinator PVP Bantul' }}</p>
        </div>
    </div>
</body>
</html>
