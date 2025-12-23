<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SchedulePreviewController extends Controller
{
    public function __invoke(Request $request)
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
            'tanggal' => '15 - 18',
            'kelas' => 'GARMEN APPAREL',
            'nomor' => 'SKA/PP/FM/13-18',
            'hal' => '1 dari 3',
            'no_terbit' => 'A',
            'no_rev' => '2',
            'tanggal_terbit' => '19 Juli 2022',
        ];

        $days = [
            ['key' => 'senin', 'label' => 'SENIN', 'date' => '14'],
            ['key' => 'selasa', 'label' => 'SELASA', 'date' => '15'],
            ['key' => 'rabu', 'label' => 'RABU', 'date' => '16'],
            ['key' => 'kamis', 'label' => 'KAMIS', 'date' => '17'],
            ['key' => 'jumat', 'label' => "JUM'AT", 'date' => '18'],
        ];

        $schedule = [
            ['type' => 'apel', 'time' => '07.30 - 08.00', 'fri' => '07.15-08.45', 'entries' => [
                'senin' => ['text' => 'Apel Pagi'], 'selasa' => ['text' => 'Apel Pagi'], 'rabu' => ['text' => 'Apel Pagi'], 'kamis' => ['text' => 'Apel Pagi'], 'jumat' => ['text' => 'Senam'],
            ]],
            ['no' => 1, 'time' => '08.00 - 08.45', 'fri' => '08.45-09.30'],
            ['no' => 2, 'time' => '08.45 - 09.30', 'fri' => '09.30-10.15'],
            ['type' => 'break', 'label' => 'BREAK'],
            ['no' => 3, 'time' => '09.45 - 10.30', 'fri' => '10.30-11.15'],
            ['no' => 4, 'time' => '10.30 - 11.15', 'fri' => '11.15-12.00'],
            ['no' => 5, 'time' => '11.15 - 12.00', 'fri' => '12.00-12.45'],
            ['type' => 'isoma', 'label' => 'ISOMA'],
            ['no' => 6, 'time' => '13.00 - 13.45', 'fri' => '13.00-13.45'],
            ['no' => 7, 'time' => '13.45 - 14.30', 'fri' => '14.15-15.00'],
            ['no' => 8, 'time' => '14.30 - 15.15', 'fri' => '15.00-15.45'],
        ];

        $dailyCodes = [
            'senin' => [
                1 => ['P.8550FOO.001.1', 'TIM'],
                2 => ['P.8550FOO.001.1', 'TIM'],
                3 => ['P.8550FOO.004.1', 'TIM'],
                4 => ['P.8550FOO.004.1', 'TIM'],
                5 => ['P.8550FOO.005.1', 'TIM'],
                6 => ['P.8550FOO.005.1', 'TIM'],
                7 => ['P.8550FOO.003.1', 'TIM'],
                8 => ['P.8550FOO.003.1', 'TIM'],
                9 => ['P.8550FOO.004.1', 'TIM'],
            ],
            'selasa' => [
                1 => ['P.8550FOO.001.1', 'TIM'],
                2 => ['P.8550FOO.001.1', 'TIM'],
                3 => ['P.8550FOO.004.1', 'TIM'],
                4 => ['P.8550FOO.004.1', 'TIM'],
                5 => ['P.8550FOO.005.1', 'TIM'],
                6 => ['P.8550FOO.005.1', 'TIM'],
                7 => ['P.8550FOO.003.1', 'TIM'],
                8 => ['P.8550FOO.003.1', 'TIM'],
                9 => ['P.8550FOO.004.1', 'TIM'],
            ],
            'rabu' => [
                1 => ['P.8550FOO.006.1', 'WV'],
                2 => ['P.8550FOO.019.1', 'WV'],
                3 => ['P.8550FOO.019.1', 'WV'],
                4 => ['P.8550FOO.019.1', 'WV'],
                5 => ['P.8550FOO.019.1', 'WV'],
                6 => ['P.8550FOO.006.3', 'WV'],
                7 => ['P.8550FOO.006.3', 'WV'],
                8 => ['P.8550FOO.010.1', 'WV'],
                9 => ['P.8550FOO.010.1', 'WV'],
            ],
            'kamis' => [
                1 => ['P.8550FOO.006.1', 'WV'],
                2 => ['P.8550FOO.019.1', 'WV'],
                3 => ['P.8550FOO.019.1', 'WV'],
                4 => ['P.8550FOO.019.1', 'WV'],
                5 => ['P.8550FOO.019.1', 'WV'],
                6 => ['P.8550FOO.006.3', 'WV'],
                7 => ['P.8550FOO.006.3', 'WV'],
                8 => ['P.8550FOO.010.1', 'WV'],
                9 => ['P.8550FOO.010.1', 'WV'],
            ],
            'jumat' => [
                1 => ['Senam', ''],
                2 => ['C.14GMT06.006.3', 'WV'],
                3 => ['C.14GMT06.006.3', 'WV'],
                4 => ['C.14GMT06.003.1', 'WV'],
                5 => ['C.14GMT06.003.3', 'WV'],
                6 => ['C.14GMT06.003.3', 'WV'],
                7 => ['C.14GMT06.003.1', 'WV'],
                8 => ['C.14GMT06.010.1', 'WV'],
                9 => ['C.14GMT06.010.1', 'WV'],
            ],
        ];

        $unitDescriptions = [
            ['code' => 'FMD', 'desc' => 'Fisik Mental Disiplin'],
            ['code' => 'GAR.CM01.003.01', 'desc' => 'Mengikuti Prosedur Kesehatan, Keselamatan Kerja (K3) penunjang'],
            ['code' => 'C.14GMT06.006.3', 'desc' => 'Menempel Interlining ke Komponen Garmen'],
            ['code' => 'C.14GMT06.010.1', 'desc' => 'Mengoperasikan Mesin Obras/Overlock'],
        ];

        $trainer = [
            'name' => 'Wanda Verdita, S.Pd.',
            'code' => 'WV',
        ];

        return view('admin.talent_pool.schedule-preview', compact('meta', 'schedule', 'dailyCodes', 'unitDescriptions', 'trainer', 'days'));
    }
}
