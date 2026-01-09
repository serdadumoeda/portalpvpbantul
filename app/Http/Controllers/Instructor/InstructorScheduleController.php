<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\InstructorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class InstructorScheduleController extends Controller
{
    public function index()
    {
        $this->authorizeInstructor();

        $schedules = InstructorSchedule::where('created_by', Auth::id())
            ->latest()
            ->get();

        return view('instructor.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $this->authorizeInstructor();

        $defaults = $this->defaults();

        return view('instructor.schedules.form', [
            'schedule' => new InstructorSchedule(),
            'action' => route('instructor.schedules.store'),
            'method' => 'POST',
            'metaJson' => $this->toJson($defaults['meta']),
            'daysJson' => $this->toJson($defaults['days']),
            'rowsJson' => $this->toJson($defaults['rows']),
            'unitsJson' => $this->toJson($defaults['units']),
            'trainerJson' => $this->toJson($defaults['trainer']),
            'signaturesJson' => $this->toJson($defaults['signatures']),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeInstructor();

        $payload = $this->validatePayload($request);

        InstructorSchedule::create($payload + ['created_by' => Auth::id()]);

        return redirect()->route('instructor.schedules.index')->with('success', 'Jadwal berhasil dibuat.');
    }

    public function edit(InstructorSchedule $schedule)
    {
        $this->authorizeInstructor();
        $this->authorizeOwner($schedule);

        return view('instructor.schedules.form', [
            'schedule' => $schedule,
            'action' => route('instructor.schedules.update', $schedule),
            'method' => 'PUT',
            'metaJson' => $this->toJson($schedule->meta ?? []),
            'daysJson' => $this->toJson($schedule->days ?? []),
            'rowsJson' => $this->toJson($schedule->rows ?? []),
            'unitsJson' => $this->toJson($schedule->unit_descriptions ?? []),
            'trainerJson' => $this->toJson($schedule->trainer ?? []),
            'signaturesJson' => $this->toJson($schedule->signatures ?? []),
        ]);
    }

    public function update(Request $request, InstructorSchedule $schedule)
    {
        $this->authorizeInstructor();
        $this->authorizeOwner($schedule);

        $payload = $this->validatePayload($request);
        $schedule->update($payload);

        return redirect()->route('instructor.schedules.index')->with('success', 'Jadwal diperbarui.');
    }

    public function destroy(InstructorSchedule $schedule)
    {
        $this->authorizeInstructor();
        $this->authorizeOwner($schedule);
        $schedule->delete();

        return redirect()->route('instructor.schedules.index')->with('success', 'Jadwal dihapus.');
    }

    public function preview(InstructorSchedule $schedule)
    {
        $this->authorizeInstructor();
        $this->authorizeOwner($schedule);

        $meta = $schedule->meta ?? [];
        $days = $schedule->days ?? [];
        $unitDescriptions = $schedule->unit_descriptions ?? [];
        $trainer = $schedule->trainer ?? [];
        $rows = $schedule->rows ?? [];

        return view('admin.talent_pool.schedule-preview', [
            'meta' => $meta,
            'days' => $days,
            'unitDescriptions' => $unitDescriptions,
            'trainer' => $trainer,
            'schedule' => $rows,
            'signatures' => $schedule->signatures ?? $this->defaults()['signatures'],
        ]);
    }

    private function authorizeOwner(InstructorSchedule $schedule): void
    {
        if ($schedule->created_by !== Auth::id()) {
            abort(403);
        }
    }

    private function authorizeInstructor(): void
    {
        $user = Auth::user();
        if (! $user || ! $user->hasAnyRole(['instructor', 'instruktur', 'admin', 'superadmin'])) {
            abort(403);
        }
    }

    private function validatePayload(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'meta_json' => 'required|string',
            'days_json' => 'required|string',
            'rows_json' => 'required|string',
            'unit_descriptions_json' => 'required|string',
            'trainer_json' => 'required|string',
            'signatures_json' => 'required|string',
        ]);

        return [
            'title' => $data['title'],
            'meta' => $this->decodeJson($data['meta_json'], 'meta_json'),
            'days' => $this->decodeJson($data['days_json'], 'days_json'),
            'rows' => $this->decodeJson($data['rows_json'], 'rows_json'),
            'unit_descriptions' => $this->decodeJson($data['unit_descriptions_json'], 'unit_descriptions_json'),
            'trainer' => $this->decodeJson($data['trainer_json'], 'trainer_json'),
            'signatures' => $this->decodeJson($data['signatures_json'], 'signatures_json'),
        ];
    }

    private function decodeJson(string $json, string $field): array
    {
        $decoded = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            throw ValidationException::withMessages([
                $field => 'Format JSON tidak valid.',
            ]);
        }

        return $decoded;
    }

    private function toJson(array $data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    private function defaults(): array
    {
        $meta = [
            'kejuruan' => 'FASHION TECHNOLOGY',
            'sub_kejuruan' => 'GARMEN APPAREL',
            'program' => 'OPERATOR GARMEN MUDA 1A',
            'pbk_ke' => 'II',
            'tahun' => '2025',
            'jenis_pelatihan' => 'INSTITUSIONAL - APBN',
            'minggu_ke' => 'I',
            'bulan' => 'JULI',
            'tanggal' => '21 - 26',
            'kelas' => 'GARMEN APPAREL',
            'nomor' => 'SKA/PP/FM/13-18',
            'hal' => '1 dari 3',
            'no_terbit' => 'A',
            'no_rev' => '2',
            'tanggal_terbit' => '19 Juli 2022',
        ];

        $days = [
            ['key' => 'senin', 'label' => 'SENIN', 'date' => '21'],
            ['key' => 'selasa', 'label' => 'SELASA', 'date' => '22'],
            ['key' => 'rabu', 'label' => 'RABU', 'date' => '23'],
            ['key' => 'kamis', 'label' => 'KAMIS', 'date' => '24'],
            ['key' => 'jumat', 'label' => "JUM'AT", 'date' => '25'],
            ['key' => 'sabtu', 'label' => 'SABTU', 'date' => '26', 'hatched' => true],
        ];

        $rows = [
            [
                'type' => 'apel',
                'no_l' => '',
                'time_mon_thu' => '07.30 - 08.00',
                'no_r' => '',
                'time_fri' => '07.15-08.45',
                'time_fri_row_span' => 2,
                'sessions' => [
                    'senin' => ['label' => 'Apel Pagi', 'col_span' => 2],
                    'selasa' => ['label' => 'Apel Pagi', 'col_span' => 2],
                    'rabu' => ['label' => 'Apel Pagi', 'col_span' => 2],
                    'kamis' => ['label' => 'Apel Pagi', 'col_span' => 2],
                    'jumat' => ['label' => 'Senam', 'col_span' => 2, 'row_span' => 4],
                ],
            ],
            [
                'no_l' => 1,
                'time_mon_thu' => '08.00 - 08.45',
                'sessions' => [
                    'senin' => ['code' => 'C.14GMT06.024.1', 'inst' => 'WV'],
                    'selasa' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'rabu' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'kamis' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                ],
            ],
            [
                'no_l' => 2,
                'time_mon_thu' => '08.45 - 09.30',
                'no_r' => 1,
                'time_fri' => '08.45-09.30',
                'sessions' => [
                    'senin' => ['code' => 'C.14GMT06.024.1', 'inst' => 'WV'],
                    'selasa' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'rabu' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'kamis' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'jumat' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                ],
            ],
            [
                'no_l' => 3,
                'time_mon_thu' => '09.30 - 10.15',
                'no_r' => 2,
                'time_fri' => '09.30-10.15',
                'sessions' => [
                    'senin' => ['code' => 'C.14GMT06.024.1', 'inst' => 'WV'],
                    'selasa' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'rabu' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'kamis' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'jumat' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                ],
            ],
            [
                'is_break' => true,
                'label' => 'BREAK',
                'time_mon_thu' => '10.15 - 10.30',
                'no_r' => 3,
                'time_fri' => '10.15-11.00',
                'sessions' => [
                    'senin' => ['label' => 'BREAK', 'col_span' => 2],
                    'selasa' => ['label' => 'BREAK', 'col_span' => 2],
                    'rabu' => ['label' => 'BREAK', 'col_span' => 2],
                    'kamis' => ['label' => 'BREAK', 'col_span' => 2],
                    'jumat' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                ],
            ],
            [
                'no_l' => 4,
                'time_mon_thu' => '10.30 - 11.15',
                'no_r' => 4,
                'time_fri' => '11.00-11.45',
                'sessions' => [
                    'senin' => ['code' => 'C.14GMT06.024.1', 'inst' => 'WV'],
                    'selasa' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'rabu' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'kamis' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'jumat' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                ],
            ],
            [
                'no_l' => 5,
                'time_mon_thu' => '11.15 - 12.00',
                'time_fri' => '11.45-12.45',
                'is_fri_special' => true,
                'sessions' => [
                    'senin' => ['code' => 'GAR.CM01.003.01', 'inst' => 'WV'],
                    'selasa' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'rabu' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'kamis' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'jumat' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                ],
            ],
            [
                'is_break' => true,
                'label' => 'ISOMA',
                'time_mon_thu' => '12.00 - 13.00',
                'sessions' => [
                    'senin' => ['label' => 'ISOMA', 'col_span' => 2],
                    'selasa' => ['label' => 'ISOMA', 'col_span' => 2],
                    'rabu' => ['label' => 'ISOMA', 'col_span' => 2],
                    'kamis' => ['label' => 'ISOMA', 'col_span' => 2],
                    'jumat' => ['label' => 'ISOMA', 'col_span' => 2],
                ],
            ],
            [
                'no_l' => 6,
                'time_mon_thu' => '13.00 - 13.45',
                'no_r' => 5,
                'time_fri' => '12.45-13.30',
                'sessions' => [
                    'senin' => ['code' => 'GAR.CM01.003.01', 'inst' => 'WV'],
                    'selasa' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'rabu' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'kamis' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'jumat' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                ],
            ],
            [
                'no_l' => 7,
                'time_mon_thu' => '13.45 - 14.30',
                'no_r' => 6,
                'time_fri' => '13.30-14.15',
                'sessions' => [
                    'senin' => ['code' => 'GAR.CM01.003.01', 'inst' => 'WV'],
                    'selasa' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'rabu' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'kamis' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'jumat' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                ],
            ],
            [
                'no_l' => 8,
                'time_mon_thu' => '14.30 - 15.15',
                'no_r' => 7,
                'time_fri' => '14.15-15.00',
                'sessions' => [
                    'senin' => ['code' => 'GAR.CM01.003.01', 'inst' => 'WV'],
                    'selasa' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'rabu' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'kamis' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                    'jumat' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                ],
            ],
            [
                'no_l' => '',
                'time_mon_thu' => '',
                'no_r' => 8,
                'time_fri' => '15.00-15.45',
                'sessions' => [
                    'jumat' => ['code' => 'C.14GMT06.008.1', 'inst' => 'WV'],
                ],
            ],
        ];

        $units = [
            ['code' => 'FMD', 'desc' => 'Fisik Mental Disiplin'],
            ['code' => 'GAR.CM01.003.01', 'desc' => 'Mengikuti Prosedur Kesehatan, Keselamatan Kerja (K3) penunjang'],
            ['code' => 'C.14GMT06.006.3', 'desc' => 'Menempel Interlining ke Komponen Garmen'],
            ['code' => 'C.14GMT06.010.1', 'desc' => 'Mengoperasikan Mesin Obras/Overlock'],
            ['code' => 'P.85SOF00.001.1', 'desc' => 'Membangun konsep diri yang positif dalam bekerja'],
            ['code' => 'P.85SOF00.003.1', 'desc' => 'Membangun integritas sebagai tenaga kerja profesional'],
            ['code' => 'P.85SOF00.004.1', 'desc' => 'Mengembangkan kemampuan berpikir kritis dalam memecahkan masalah dan mencari solusi'],
            ['code' => 'P.85SOF00.005.1', 'desc' => 'Membentuk tanggung jawab dan komitmen dalam bekerja'],
            ['code' => 'P.85SOF00.006.1', 'desc' => 'Meningkatkan standar etika dan etiket di lingkungan kerja'],
            ['code' => 'P.85SOF00.019.1', 'desc' => 'Mengembangkan kemampuan bekerja sama dalam tim'],
        ];

        $trainer = [
            'name' => 'Wanda Verdita, S.Pd.',
            'code' => 'WV',
        ];

        $signatures = [
            'knowing_title_1' => 'Mengetahui/Menyetujui:',
            'knowing_title_2' => 'An. Kepala BPVP Surakarta',
            'knowing_title_3' => 'Koordinator Satuan Pelayanan Bantul',
            'knowing_name' => 'RADEN ROHADIJANTO, S.E., M.Sc.',
            'knowing_nip' => 'NIP 19690719 199603 1 003',
            'mid_left_role' => 'Ketua Kejuruan',
            'mid_left_program' => 'GARMEN APPAREL',
            'mid_right_role' => 'Ketua',
            'mid_right_program' => 'Program Pelatihan',
            'mid_left_name' => 'PURI ARIMA K., S.T.',
            'mid_left_nip' => 'NIP 19850103 200901 2 004',
            'mid_right_name' => $trainer['name'],
            'mid_right_nip' => 'NIP 19920920 201801 2 004',
        ];

        return compact('meta', 'days', 'rows', 'units', 'trainer', 'signatures');
    }
}
