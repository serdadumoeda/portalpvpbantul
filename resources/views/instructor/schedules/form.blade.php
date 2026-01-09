@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">{{ $schedule->exists ? 'Edit' : 'Tambah' }} Jadwal Instruktur</h4>
        <small class="text-muted">Isi meta dan data jadwal sesuai struktur template.</small>
    </div>
    <a href="{{ route('instructor.schedules.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-semibold mb-1">Periksa input berikut:</div>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $action }}" method="POST" id="scheduleForm">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Jadwal</label>
                <input type="text" name="title" value="{{ old('title', $schedule->title) }}" class="form-control" required>
            </div>

            <div class="border rounded p-3 bg-light-subtle mb-3">
                <div class="fw-semibold mb-2">Identitas Jadwal</div>
                @php($metaDefaults = json_decode($metaJson, true))
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Kejuruan</label>
                        <input type="text" class="form-control form-control-sm meta-input" data-key="kejuruan" value="{{ $metaDefaults['kejuruan'] ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Sub Kejuruan</label>
                        <input type="text" class="form-control form-control-sm meta-input" data-key="sub_kejuruan" value="{{ $metaDefaults['sub_kejuruan'] ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small mb-1">Program</label>
                        <input type="text" class="form-control form-control-sm meta-input" data-key="program" value="{{ $metaDefaults['program'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small mb-1">PBK Ke</label>
                        <input type="text" class="form-control form-control-sm meta-input" data-key="pbk_ke" value="{{ $metaDefaults['pbk_ke'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small mb-1">Tahun</label>
                        <input type="text" class="form-control form-control-sm meta-input" data-key="tahun" value="{{ $metaDefaults['tahun'] ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label text-muted small mb-1">Minggu Ke</label>
                        <input type="text" class="form-control form-control-sm meta-input" data-key="minggu_ke" value="{{ $metaDefaults['minggu_ke'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">Bulan</label>
                        <input type="text" class="form-control form-control-sm meta-input" data-key="bulan" value="{{ $metaDefaults['bulan'] ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">Tanggal</label>
                        <input type="text" class="form-control form-control-sm meta-input" data-key="tanggal" value="{{ $metaDefaults['tanggal'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small mb-1">Kelas</label>
                        <input type="text" class="form-control form-control-sm meta-input" data-key="kelas" value="{{ $metaDefaults['kelas'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small mb-1">Nomor</label>
                        <input type="text" class="form-control form-control-sm meta-input" data-key="nomor" value="{{ $metaDefaults['nomor'] ?? '' }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small mb-1">Hal / No Rev</label>
                        <div class="d-flex gap-1">
                            <input type="text" class="form-control form-control-sm meta-input" data-key="hal" value="{{ $metaDefaults['hal'] ?? '' }}" placeholder="Hal">
                            <input type="text" class="form-control form-control-sm meta-input" data-key="no_terbit" value="{{ $metaDefaults['no_terbit'] ?? '' }}" placeholder="Terbit">
                            <input type="text" class="form-control form-control-sm meta-input" data-key="no_rev" value="{{ $metaDefaults['no_rev'] ?? '' }}" placeholder="Rev">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small mb-1">Tanggal Terbit</label>
                        <input type="text" class="form-control form-control-sm meta-input" data-key="tanggal_terbit" value="{{ $metaDefaults['tanggal_terbit'] ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="border rounded p-3 bg-light-subtle mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="fw-semibold">Hari & Tanggal</div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addDay">Tambah Hari</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0" id="daysTable">
                        <thead>
                            <tr><th>Key</th><th>Label</th><th>Tanggal</th><th>Hatched</th><th></th></tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <small class="text-muted">Contoh key: senin, selasa, rabu, kamis, jumat, sabtu.</small>
            </div>

            <div class="mt-3 border rounded p-3 bg-light-subtle">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="fw-semibold">Baris Jadwal (ringkas)</div>
                        <small class="text-muted">Isi waktu & per hari di satu tabel.</small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addRow">Tambah Baris</button>
                        <button type="button" class="btn btn-sm btn-outline-success" id="duplicateWeek">Tambah Minggu Berikutnya</button>
                    </div>
                </div>
                <div class="table-responsive" style="overflow-x:auto;">
                    <table class="table table-sm align-middle w-100" id="rowsTable" style="table-layout:auto; min-width:1200px;">
                        <thead>
                            <tr>
                                <th>No L</th><th>Waktu S-K</th><th>No R</th><th>Waktu Jum'at</th><th>Rowspan Jmt</th><th>Break?</th><th>Label</th>
                                @foreach(json_decode($daysJson, true) as $d)
                                    <th>{{ $d['label'] }}<div class="small text-muted">{{ $d['date'] }}</div></th>
                                @endforeach
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-lg-7">
                    <div class="border rounded p-3 bg-light-subtle">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fw-semibold">Keterangan Unit</div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addUnit">Tambah Unit</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0" id="unitsTable">
                                <thead><tr><th>Kode</th><th>Deskripsi</th><th></th></tr></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="border rounded p-3 bg-light-subtle h-100">
                        <div class="fw-semibold mb-2">Trainer</div>
                        @php($trainer = json_decode($trainerJson, true))
                        <div class="mb-2">
                            <label class="form-label text-muted small mb-1">Kode</label>
                            <input type="text" class="form-control form-control-sm" id="trainerCode" value="{{ $trainer['code'] ?? '' }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label text-muted small mb-1">Nama</label>
                            <input type="text" class="form-control form-control-sm" id="trainerName" value="{{ $trainer['name'] ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>

            @php($sig = json_decode($signaturesJson ?? '', true))
            @php($sig = $sig ?: [
                'knowing_title_1' => 'Mengetahui/Menyetujui:',
                'knowing_title_2' => 'An. Kepala BPVP Surakarta',
                'knowing_title_3' => 'Koordinator Satuan Pelayanan Bantul',
                'knowing_name' => 'RADEN ROHADIJANTO, S.E., M.Sc.',
                'knowing_nip' => 'NIP 19690719 199603 1 003',
                'mid_left_role' => 'Ketua Kejuruan',
                'mid_left_program' => 'GARMEN APPAREL',
                'mid_left_name' => 'PURI ARIMA K., S.T.',
                'mid_left_nip' => 'NIP 19850103 200901 2 004',
                'mid_right_role' => 'Ketua',
                'mid_right_program' => 'Program Pelatihan',
                'mid_right_name' => 'Wanda Verdita, S.Pd.',
                'mid_right_nip' => 'NIP 19920920 201801 2 004',
            ])
            <div class="border rounded p-3 bg-light-subtle mt-3">
                <div class="fw-semibold mb-2">Tanda Tangan</div>
                <div class="row g-3">
                    <div class="col-lg-4">
                        <label class="form-label text-muted small mb-1">Judul 1</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="knowing_title_1" value="{{ $sig['knowing_title_1'] ?? '' }}">
                        <label class="form-label text-muted small mb-1 mt-2">Judul 2</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="knowing_title_2" value="{{ $sig['knowing_title_2'] ?? '' }}">
                        <label class="form-label text-muted small mb-1 mt-2">Judul 3</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="knowing_title_3" value="{{ $sig['knowing_title_3'] ?? '' }}">
                        <label class="form-label text-muted small mb-1 mt-2">Nama Penandatangan</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="knowing_name" value="{{ $sig['knowing_name'] ?? '' }}">
                        <label class="form-label text-muted small mb-1 mt-2">NIP</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="knowing_nip" value="{{ $sig['knowing_nip'] ?? '' }}">
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label text-muted small mb-1">Role Kiri Tengah</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="mid_left_role" value="{{ $sig['mid_left_role'] ?? '' }}">
                        <label class="form-label text-muted small mb-1 mt-2">Program Kiri</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="mid_left_program" value="{{ $sig['mid_left_program'] ?? '' }}">
                        <label class="form-label text-muted small mb-1 mt-2">Nama Kiri</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="mid_left_name" value="{{ $sig['mid_left_name'] ?? '' }}">
                        <label class="form-label text-muted small mb-1 mt-2">NIP Kiri</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="mid_left_nip" value="{{ $sig['mid_left_nip'] ?? '' }}">
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label text-muted small mb-1">Role Kanan Tengah</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="mid_right_role" value="{{ $sig['mid_right_role'] ?? '' }}">
                        <label class="form-label text-muted small mb-1 mt-2">Program Kanan</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="mid_right_program" value="{{ $sig['mid_right_program'] ?? '' }}">
                        <label class="form-label text-muted small mb-1 mt-2">Nama Kanan</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="mid_right_name" value="{{ $sig['mid_right_name'] ?? '' }}">
                        <label class="form-label text-muted small mb-1 mt-2">NIP Kanan</label>
                        <input type="text" class="form-control form-control-sm sig-input" data-key="mid_right_nip" value="{{ $sig['mid_right_nip'] ?? '' }}">
                    </div>
                </div>
            </div>

            {{-- Hidden JSON fields (diisi oleh script) --}}
            <textarea name="meta_json" id="metaJson" class="d-none" required>{{ old('meta_json', $metaJson) }}</textarea>
            <textarea name="days_json" id="daysJson" class="d-none" required>{{ old('days_json', $daysJson) }}</textarea>
            <textarea name="rows_json" id="rowsJson" class="d-none" required>{{ old('rows_json', $rowsJson) }}</textarea>
            <textarea name="unit_descriptions_json" id="unitsJson" class="d-none" required>{{ old('unit_descriptions_json', $unitsJson) }}</textarea>
            <textarea name="trainer_json" id="trainerJson" class="d-none" required>{{ old('trainer_json', $trainerJson) }}</textarea>
            <textarea name="signatures_json" id="signaturesJson" class="d-none" required>{{ old('signatures_json', $signaturesJson ?? '') }}</textarea>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('instructor.schedules.index') }}" class="btn btn-light">Batal</a>
            <button class="btn btn-primary">{{ $schedule->exists ? 'Simpan Perubahan' : 'Simpan Jadwal' }}</button>
        </div>
    </div>
</form>

<style>
.monospace { font-family: ui-monospace, SFMono-Regular, Consolas, "Liberation Mono", Menlo, monospace; }
.row-card { border:1px solid #dee2e6; border-radius:10px; padding:12px; background:#fff; }
.row-card h6 { font-size:14px; margin-bottom:8px; }
.row-grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(180px,1fr)); gap:8px; }
.session-grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(180px,1fr)); gap:8px; }
.session-box { border:1px dashed #ced4da; border-radius:8px; padding:8px; background:#f8f9fa; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const state = {
        meta: @json(json_decode($metaJson, true)),
        days: @json(json_decode($daysJson, true)),
        rows: @json(json_decode($rowsJson, true)),
        units: @json(json_decode($unitsJson, true)),
        trainer: @json(json_decode($trainerJson, true)),
        signatures: @json($sig),
    };
    const baseDaysTemplate = @json(json_decode($daysJson, true));
    const baseRowsTemplate = @json(json_decode($rowsJson, true));

    const metaInputs = document.querySelectorAll('.meta-input');
    const daysTableBody = document.querySelector('#daysTable tbody');
    const unitsTableBody = document.querySelector('#unitsTable tbody');
    const rowsContainer = document.getElementById('rowsContainer'); // hidden (legacy)
    const trainerCode = document.getElementById('trainerCode');
    const trainerName = document.getElementById('trainerName');
    const duplicateWeekBtn = document.getElementById('duplicateWeek');

    function syncHidden() {
        document.getElementById('metaJson').value = JSON.stringify(state.meta);
        document.getElementById('daysJson').value = JSON.stringify(state.days);
        document.getElementById('rowsJson').value = JSON.stringify(state.rows);
        document.getElementById('unitsJson').value = JSON.stringify(state.units);
        document.getElementById('trainerJson').value = JSON.stringify(state.trainer);
        document.getElementById('signaturesJson').value = JSON.stringify(state.signatures);
    }

    function renderDays() {
        daysTableBody.innerHTML = '';
        state.days.forEach((d, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input class="form-control form-control-sm" value="${d.key || ''}" data-idx="${idx}" data-field="key"></td>
                <td><input class="form-control form-control-sm" value="${d.label || ''}" data-idx="${idx}" data-field="label"></td>
                <td><input class="form-control form-control-sm" value="${d.date || ''}" data-idx="${idx}" data-field="date"></td>
                <td class="text-center"><input type="checkbox" class="form-check-input" ${d.hatched ? 'checked' : ''} data-idx="${idx}" data-field="hatched"></td>
                <td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger" data-remove-day="${idx}"><i class="fas fa-trash"></i></button></td>
            `;
            daysTableBody.appendChild(tr);
        });
    }

    function renderUnits() {
        unitsTableBody.innerHTML = '';
        state.units.forEach((u, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input class="form-control form-control-sm" value="${u.code || ''}" data-idx="${idx}" data-field="code" data-type="unit"></td>
                <td><input class="form-control form-control-sm" value="${u.desc || ''}" data-idx="${idx}" data-field="desc" data-type="unit"></td>
                <td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger" data-remove-unit="${idx}"><i class="fas fa-trash"></i></button></td>
            `;
            unitsTableBody.appendChild(tr);
        });
    }

    function renderRows() {
        const tbody = document.querySelector('#rowsTable tbody');
        if (!tbody) return;
        tbody.innerHTML = '';
        state.rows.forEach((r, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input class="form-control form-control-sm" value="${r.no_l || ''}" data-row="${idx}" data-field="no_l"></td>
                <td><input class="form-control form-control-sm" value="${r.time_mon_thu || ''}" data-row="${idx}" data-field="time_mon_thu"></td>
                <td><input class="form-control form-control-sm" value="${r.no_r || ''}" data-row="${idx}" data-field="no_r"></td>
                <td><input class="form-control form-control-sm" value="${r.time_fri || ''}" data-row="${idx}" data-field="time_fri"></td>
                <td><input type="number" min="1" class="form-control form-control-sm" value="${r.time_fri_row_span || 1}" data-row="${idx}" data-field="time_fri_row_span"></td>
                <td class="text-center"><input class="form-check-input" type="checkbox" ${r.is_break ? 'checked' : ''} data-row="${idx}" data-field="is_break"></td>
                <td><input class="form-control form-control-sm" value="${r.label || ''}" data-row="${idx}" data-field="label" placeholder="BREAK/ISOMA/Senam"></td>
            `;
            state.days.forEach((d) => {
                const s = r.sessions?.[d.key] || {};
                const val = s.label || s.code || '';
                tr.innerHTML += `<td><input class="form-control form-control-sm session-input" data-row="${idx}" data-day="${d.key}" data-field="${s.label ? 'label' : 'code'}" value="${val}"></td>`;
            });
            tr.innerHTML += `<td class="text-end"><button type="button" class="btn btn-sm btn-outline-danger" data-remove-row="${idx}"><i class="fas fa-trash"></i></button></td>`;
            tbody.appendChild(tr);
        });
    }

    // Initial render
    renderDays();
    renderUnits();
    renderRows();
    syncHidden();

    // Meta handlers
    metaInputs.forEach(inp => {
        inp.addEventListener('input', () => {
            const key = inp.dataset.key;
            state.meta[key] = inp.value;
            syncHidden();
        });
    });

    // Days handlers
    document.getElementById('addDay').addEventListener('click', () => {
        state.days.push({ key: '', label: '', date: '', hatched: false });
        renderDays();
        renderRows(); // ensure sessions structure align
        syncHidden();
    });
    daysTableBody.addEventListener('input', (e) => {
        const idx = e.target.dataset.idx;
        const field = e.target.dataset.field;
        if (idx === undefined || field === undefined) return;
        state.days[idx][field] = (e.target.type === 'checkbox') ? e.target.checked : e.target.value;
        renderRows();
        syncHidden();
    });
    daysTableBody.addEventListener('click', (e) => {
        const idx = e.target.closest('[data-remove-day]')?.dataset.removeDay;
        if (idx !== undefined) {
            state.days.splice(idx, 1);
            renderDays();
            renderRows();
            syncHidden();
        }
    });

    // Units handlers
    document.getElementById('addUnit').addEventListener('click', () => {
        state.units.push({ code: '', desc: '' });
        renderUnits();
        syncHidden();
    });
    unitsTableBody.addEventListener('input', (e) => {
        const idx = e.target.dataset.idx;
        const field = e.target.dataset.field;
        if (e.target.dataset.type === 'unit') {
            state.units[idx][field] = e.target.value;
            syncHidden();
        }
    });
    unitsTableBody.addEventListener('click', (e) => {
        const idx = e.target.closest('[data-remove-unit]')?.dataset.removeUnit;
        if (idx !== undefined) {
            state.units.splice(idx, 1);
            renderUnits();
            syncHidden();
        }
    });

    // Rows handlers
    document.getElementById('addRow').addEventListener('click', () => {
        const templateSessions = {};
        state.days.forEach(d => templateSessions[d.key] = { code:'', inst:'', label:'', col_span:2, row_span:1 });
        state.rows.push({
            no_l: '', time_mon_thu: '', no_r: '', time_fri: '', time_fri_row_span: 1,
            is_fri_special: false, is_break: false, label: '', sessions: templateSessions
        });
        renderRows();
        syncHidden();
    });

    document.getElementById('rowsTable').addEventListener('input', (e) => {
        const rowIdx = e.target.dataset.row;
        const dayKey = e.target.dataset.day;
        const field = e.target.dataset.field;
        if (rowIdx === undefined || field === undefined) return;

        if (dayKey) {
            state.rows[rowIdx].sessions = state.rows[rowIdx].sessions || {};
            state.rows[rowIdx].sessions[dayKey] = state.rows[rowIdx].sessions[dayKey] || {};
            state.rows[rowIdx].sessions[dayKey][field] = e.target.value;
        } else {
            const val = (e.target.type === 'checkbox') ? e.target.checked : (e.target.type === 'number' ? Number(e.target.value) : e.target.value);
            state.rows[rowIdx][field] = val;
        }
        syncHidden();
    });

    document.getElementById('rowsTable').addEventListener('click', (e) => {
        const rowIdx = e.target.closest('[data-remove-row]')?.dataset.removeRow;
        if (rowIdx !== undefined) {
            state.rows.splice(rowIdx, 1);
            renderRows();
            syncHidden();
        }
    });

    // Duplikat minggu berikutnya: duplikat days + rows dari template minggu pertama
    duplicateWeekBtn.addEventListener('click', () => {
        if (!state.days.length || !state.rows.length) return;

        const baseRowsCount = baseRowsTemplate.length;
        const weekIndex = Math.floor(state.days.length / baseDaysTemplate.length) + 1;
        const dateOffset = 7 * (weekIndex - 1);

        const newDays = baseDaysTemplate.map(d => ({
            ...d,
            key: `${d.key}_w${weekIndex}`,
            label: `${d.label} (M${weekIndex})`,
            date: d.date ? Number(d.date) + dateOffset : '',
        }));

        const templateRows = state.rows.slice(0, baseRowsCount);
        const newRows = templateRows.map(r => {
            const sessions = {};
            baseDaysTemplate.forEach(d => {
                const src = r.sessions?.[d.key] || { code: '', inst: '', label: '', col_span: 2, row_span: 1 };
                sessions[`${d.key}_w${weekIndex}`] = { ...src };
            });
            return { ...r, sessions };
        });

        state.days = [...state.days, ...newDays];
        state.rows = [...state.rows, ...newRows];
        renderDays();
        renderRows();
        syncHidden();
    });

    // Trainer
    trainerCode.addEventListener('input', () => { state.trainer.code = trainerCode.value; syncHidden(); });
    trainerName.addEventListener('input', () => { state.trainer.name = trainerName.value; syncHidden(); });

    document.querySelectorAll('.sig-input').forEach(inp => {
        inp.addEventListener('input', () => {
            const key = inp.dataset.key;
            state.signatures[key] = inp.value;
            syncHidden();
        });
    });

    // Submit sync safeguard
    document.getElementById('scheduleForm').addEventListener('submit', () => syncHidden());
});
</script>
@endpush
@endsection
