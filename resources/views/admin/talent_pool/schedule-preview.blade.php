@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">Jadwal Pelatihan (Preview)</h4>
        <small class="text-muted">Tampilan template jadwal instansi; klik “Download/Print” untuk PDF.</small>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
            <i class="fas fa-file-pdf"></i> Download / Print
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="schedule-sheet print-area">
            <div class="header-grid">
                <div class="logo-cell">
                    <div class="logo-placeholder"></div>
                </div>
                <div class="title-cell text-center">
                    <div class="fw-bold title-line">KEMENTERIAN KETENAGAKERJAAN RI</div>
                    <div class="title-line">DIREKTORAT JENDERAL PEMBINAAN PELATIHAN VOKASI DAN PRODUKTIVITAS</div>
                    <div class="fw-bold title-line">BALAI PELATIHAN VOKASI DAN PRODUKTIVITAS SURAKARTA</div>
                    <div class="title-line">Jalan Bhayangkara No. 38 Telp/Fax. (0271) 714885 Surakarta</div>
                    <div class="title-line">Laman : http://www.naker.go.id</div>
                </div>
                <div class="badge-cell">
                    <table class="badge-table badge-tight">
                        <tr><td class="label">Nomor</td><td class="value text-center">{{ $meta['nomor'] }}</td></tr>
                        <tr><td class="label">Hal</td><td class="value text-center">{{ $meta['hal'] }}</td></tr>
                        <tr>
                            <td class="label">No Terbit / Rev</td>
                            <td class="value text-center">{{ $meta['no_terbit'] }} / {{ $meta['no_rev'] }}</td>
                        </tr>
                        <tr><td class="label">Tanggal terbit</td><td class="value text-center">{{ $meta['tanggal_terbit'] }}</td></tr>
                    </table>
                </div>
            </div>

            <div class="headline">JADWAL - PELATIHAN</div>
            </div>

            <div class="meta-wrapper">
                <table class="meta-table two-col flex-2">
                    <tr>
                        <td class="label">KEJURUAN</td><td class="value">{{ $meta['kejuruan'] }}</td>
                        <td class="label">JENIS PELATIHAN</td><td class="value">{{ $meta['jenis_pelatihan'] }}</td>
                    </tr>
                    <tr>
                        <td class="label">SUB KEJURUAN</td><td class="value">{{ $meta['sub_kejuruan'] }}</td>
                        <td class="label">MINGGU KE</td><td class="value">{{ $meta['minggu_ke'] }}</td>
                    </tr>
                    <tr>
                        <td class="label">PROGRAM PELATIHAN</td><td class="value">{{ $meta['program'] }}</td>
                        <td class="label">BULAN</td><td class="value">{{ $meta['bulan'] }}</td>
                    </tr>
                    <tr>
                        <td class="label">PBK ANGKATAN KE</td><td class="value">{{ $meta['pbk_ke'] }}</td>
                        <td class="label">TANGGAL</td><td class="value">{{ $meta['tanggal'] }}</td>
                    </tr>
                    <tr>
                        <td class="label">TAHUN</td><td class="value">{{ $meta['tahun'] }}</td>
                        <td class="label"></td><td class="value"></td>
                    </tr>
                </table>

                <table class="meta-table badge-table">
                    <tr><td class="label">Nomor</td><td class="value">{{ $meta['nomor'] }}</td></tr>
                    <tr><td class="label">Hal</td><td class="value">{{ $meta['hal'] }}</td></tr>
                    <tr><td class="label">No Terbit / Rev</td><td class="value">{{ $meta['no_terbit'] }} / {{ $meta['no_rev'] }}</td></tr>
                    <tr><td class="label">Tanggal terbit</td><td class="value">{{ $meta['tanggal_terbit'] }}</td></tr>
                </table>
            </div>

            <table class="schedule-table">
                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2" class="waktukol">WAKTU SENIN-KAMIS</th>
                        <th rowspan="2" class="waktukol">WAKTU JUM'AT</th>
                        <th rowspan="2" class="kegiatan">KEGIATAN</th>
                        @foreach($days as $day)
                            <th colspan="2">{{ $day['label'] }}<br>{{ $day['date'] }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @for($i=0;$i<count($days);$i++)
                            <th class="kode">KODE UNIT</th><th class="inst">INST.</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedule as $row)
                        @if(($row['type'] ?? '') === 'apel')
                            <tr class="apel-row">
                                <td class="center"></td>
                                <td class="center">{{ $row['time'] }}</td>
                                <td class="center">{{ $row['fri'] }}</td>
                                <td class="center fw-semibold">APEL</td>
                                @foreach($days as $day)
                                    @php($entry = $row['entries'][$day['key']] ?? ['text' => ''])
                                    <td colspan="2" class="center fw-semibold">{{ $entry['text'] }}</td>
                                @endforeach
                            </tr>
                            @continue
                        @endif
                        @if(($row['type'] ?? '') === 'break')
                            <tr class="break-row">
                                <td class="center"></td>
                                <td class="center"></td>
                                <td class="center"></td>
                                <td class="center"></td>
                                <td colspan="{{ count($days)*2 }}" class="center">{{ $row['label'] ?? 'BREAK' }}</td>
                            </tr>
                            @continue
                        @endif
                        @if(($row['type'] ?? '') === 'isoma')
                            <tr class="isoma-row">
                                <td class="center"></td>
                                <td class="center"></td>
                                <td class="center"></td>
                                <td class="center"></td>
                                <td colspan="{{ count($days)*2 }}" class="center">{{ $row['label'] ?? 'ISOMA' }}</td>
                            </tr>
                            @continue
                        @endif
                        <tr>
                            <td class="center">{{ $row['no'] ?? '' }}</td>
                            <td class="center">{{ $row['time'] ?? '' }}</td>
                            <td class="center">{{ $row['fri'] ?? '' }}</td>
                            @php($pembukaan = ['P','E','M','B','U','K','A','A','N'])
                            <td class="center">{{ $pembukaan[($row['no'] ?? 1)-1] ?? '' }}</td>
                            @foreach($days as $day)
                                @php($entry = $dailyCodes[$day['key']][$row['no'] ?? 0] ?? ['', ''])
                                <td class="center">{{ $entry[0] ?? '' }}</td>
                                <td class="center">{{ $entry[1] ?? '' }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3">
                <div class="fw-semibold">Keterangan Unit Kompetensi :</div>
                <table class="kompetensi-table">
                    <tr>
                        <th>No.</th><th>Kode</th><th>Deskripsi</th>
                    </tr>
                    @foreach($unitDescriptions as $idx => $u)
                        <tr>
                            <td class="center">{{ $idx+1 }}</td>
                            <td>{{ $u['code'] }}</td>
                            <td>{{ $u['desc'] }}</td>
                        </tr>
                    @endforeach
                </table>
                <div class="fw-semibold mt-3">Kode Nama Instruktur/Pelatih :</div>
                <div class="small">{{ $trainer['code'] }} : {{ $trainer['name'] }}</div>
            </div>

            <div class="ttd-table">
                <div class="ttd-block">
                    <div class="fw-semibold">Mengetahui/Menyetujui:</div>
                    <div>An. Kepala BPVP Surakarta</div>
                    <div>Koordinator Satuan Pelayanan Bantul</div>
                </div>
                <div class="ttd-block text-center">
                    <div class="fw-semibold">{{ $meta['kelas'] }}</div>
                    <div>Program Pelatihan</div>
                </div>
            </div>

            <div class="ttd-names">
                <div class="text-center">
                    <div class="fw-semibold">RADEN ROHADJIANTO, S.E., M.Sc.</div>
                    <div class="small">NIP 19690719 199603 1 003</div>
                </div>
                <div class="text-center">
                    <div class="fw-semibold">PUR ARIMA K., S.T.</div>
                    <div class="small">NIP 19850103 200902 1 004</div>
                </div>
                <div class="text-center">
                    <div class="fw-semibold">{{ $trainer['name'] }}</div>
                    <div class="small">NIP 19890209 201801 2 004</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.schedule-sheet { font-size: 12px; color:#111; }
.schedule-sheet .header { margin-bottom: 10px; }
.meta-table { width: 100%; border-collapse: collapse; margin-bottom: 12px; table-layout: fixed; }
.meta-table.two-col td { padding: 6px 6px; border: 1px solid #000; font-size: 11px; }
.meta-table .label { font-weight: 600; background:#f5f5f5; width: 15%; }
.meta-table .value { font-weight: 600; }
.meta-wrapper { display: grid; grid-template-columns: 2fr 1fr; gap: 8px; align-items: start; margin-bottom: 12px; }
.badge-table td { padding: 6px 6px; border: 1px solid #000; font-size: 11px; }
.badge-table .label { background:#f5f5f5; font-weight: 700; width: 50%; }
.badge-table .value { font-weight: 700; }
.badge-tight td { padding: 6px 8px; }
.header-grid { display: grid; grid-template-columns: 1fr 3fr 1.2fr; align-items: stretch; margin-bottom: 4px; }
.logo-cell { display: flex; align-items: center; justify-content: center; }
.logo-placeholder { width: 110px; height: 110px; border: 1px solid #ccc; background: #f0f4f8; border-radius: 8px; }
.title-cell .title-line { font-size: 13px; }
.headline { margin: 6px 0 10px; background: #000; color: #fff; text-align: center; font-weight: 800; font-size: 18px; padding: 6px 0; letter-spacing: 0.5px; }
.schedule-table { width: 100%; border-collapse: collapse; }
.schedule-table th, .schedule-table td { border: 1px solid #000; padding: 6px 4px; font-size: 11px; }
.schedule-table .waktukol { width: 9%; }
.schedule-table .kode { width: 8%; }
.schedule-table .inst { width: 5%; }
.schedule-table .kegiatan { width: 6%; }
.schedule-table .center { text-align: center; }
.apel-row td { font-weight: 600; background:#eef2ff; }
.break-row td, .isoma-row td { font-weight: 700; background:#f5f5f5; }
.ttd-table { display: flex; gap: 16px; margin-top: 16px; }
.ttd-block { flex: 1; }
.ttd-names { display: grid; grid-template-columns: repeat(3,1fr); margin-top: 16px; gap: 8px; }
.kompetensi-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
.kompetensi-table th, .kompetensi-table td { border: 1px solid #000; padding: 4px 6px; font-size: 11px; }
.kompetensi-table th { background:#f5f5f5; }
@media print {
    body * { visibility: hidden; }
    .print-area, .print-area * { visibility: visible; }
    .print-area { position: absolute; left: 0; top: 0; width: 100%; }
    .card, .card-body { border: none !important; box-shadow: none !important; }
    button { display: none; }
    body { background: #fff; }
}
</style>
@endsection
