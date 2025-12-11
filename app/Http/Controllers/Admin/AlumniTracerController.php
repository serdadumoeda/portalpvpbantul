<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlumniTracer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

        return redirect()->route('admin.alumni-tracer.index')->with('success', 'Data alumni berhasil diverifikasi.');
    }

    public function destroy(AlumniTracer $alumni_tracer)
    {
        $alumni_tracer->delete();
        return redirect()->route('admin.alumni-tracer.index')->with('success', 'Data tracer dihapus.');
    }
}
