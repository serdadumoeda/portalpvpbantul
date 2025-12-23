<?php

namespace App\Console\Commands;

use App\Models\SurveyAnswer;
use App\Models\SurveyInstance;
use Illuminate\Console\Command;

class SurveyInstanceReport extends Command
{
    protected $signature = 'survey:instance-report {instance}';

    protected $description = 'Tampilkan agregat sederhana untuk survey instance (total respons, rata-rata numeric, top pilihan).';

    public function handle(): int
    {
        $instanceId = $this->argument('instance');
        $instance = SurveyInstance::with(['survey', 'course', 'instructor'])->find($instanceId);

        if (! $instance) {
            $this->error('Instance tidak ditemukan.');
            return self::FAILURE;
        }

        $totalResponses = $instance->responses()->count();
        $avgNumeric = SurveyAnswer::whereHas('response', function ($q) use ($instance) {
                $q->where('survey_instance_id', $instance->id);
            })
            ->avg('answer_numeric');

        $this->info("Survey: {$instance->survey->title}");
        $this->info("Kelas: " . ($instance->course->title ?? '-'));
        $this->info("Instruktur: " . ($instance->instructor->name ?? '-'));
        $this->info("Status: {$instance->status}");
        $this->info("Respons: {$totalResponses}");
        $this->info("Rata-rata numeric: " . ($avgNumeric ? number_format($avgNumeric, 2) : '-'));

        // Hitung 3 opsi teratas dari jawaban text sebagai indikasi cepat (simple tokenization)
        $topWords = SurveyAnswer::whereHas('response', function ($q) use ($instance) {
                $q->where('survey_instance_id', $instance->id);
            })
            ->whereNotNull('answer_text')
            ->pluck('answer_text')
            ->flatMap(function ($text) {
                $tokens = preg_split('/\s+/', strtolower(strip_tags($text)));
                return collect($tokens)->filter(fn ($t) => strlen($t) > 3);
            })
            ->countBy()
            ->sortDesc()
            ->take(3);

        if ($topWords->isNotEmpty()) {
            $this->info('Top kata (indikatif):');
            foreach ($topWords as $word => $count) {
                $this->line("- {$word}: {$count}");
            }
        }

        return self::SUCCESS;
    }
}
