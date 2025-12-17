<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AlumniTracerVerified;
use App\Models\AlumniTracer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AlumniTracerController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $year = $request->input('year');

        $query = AlumniTracer::query()->orderByDesc('created_at');
        if ($status) {
            $query->where('status', $status);
        }
        if ($year) {
            $query->where('graduation_year', $year);
        }

        $responses = $query->paginate(15)->withQueryString();

        $metrics = [
            'total' => AlumniTracer::count(),
            'employed' => AlumniTracer::where('status', 'employed')->count(),
            'entrepreneur' => AlumniTracer::where('status', 'entrepreneur')->count(),
            'studying' => AlumniTracer::where('status', 'studying')->count(),
        ];

        $years = AlumniTracer::select('graduation_year')->whereNotNull('graduation_year')->distinct()->orderByDesc('graduation_year')->pluck('graduation_year');

        return view('admin.alumni_tracer.index', compact('responses', 'metrics', 'status', 'year', 'years'));
    }

    public function show(AlumniTracer $alumni_tracer)
    {
        return view('admin.alumni_tracer.show', compact('alumni_tracer'));
    }

    public function verify(AlumniTracer $alumni_tracer)
    {
        $alumni_tracer->update([
            'is_verified' => true,
            'verified_at' => Carbon::now(),
        ]);

        if ($alumni_tracer->email) {
            Mail::to($alumni_tracer->email)->send(new AlumniTracerVerified($alumni_tracer));
        }

        return redirect()->route('admin.alumni-tracer.index')->with('success', 'Data alumni berhasil diverifikasi.');
    }

    public function destroy(AlumniTracer $alumni_tracer)
    {
        $alumni_tracer->delete();
        return redirect()->route('admin.alumni-tracer.index')->with('success', 'Data tracer dihapus.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'alumni-tracers-' . now()->format('YmdHis') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $columns = [
            'Nomor Alumni',
            'Nama',
            'Email',
            'Program',
            'Status',
            'Perusahaan',
            'Gaji',
            'Lulus',
            'Terverifikasi',
            'Tanggal Input',
        ];

        $response = response()->streamDownload(function () use ($columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            AlumniTracer::orderByDesc('created_at')->chunk(200, function ($tracers) use ($handle) {
                foreach ($tracers as $tracer) {
                    fputcsv($handle, [
                        $tracer->alumni_number,
                        $tracer->full_name,
                        $tracer->email,
                        $tracer->program_name,
                        ucfirst($tracer->status),
                        $tracer->company_name,
                        $tracer->salary_range,
                        $tracer->graduation_year,
                        $tracer->is_verified ? 'Ya' : 'Belum',
                        $tracer->created_at->toDateTimeString(),
                    ]);
                }
            });
            fclose($handle);
        }, $filename, $headers);

        return $response;
    }
}
