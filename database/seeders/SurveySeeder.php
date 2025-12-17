<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    public function run(): void
    {
        if (Survey::where('slug', 'survey-kepuasan-layanan')->exists()) {
            return;
        }

        $survey = Survey::create([
            'title' => 'Survey Kepuasan Layanan BPVP',
            'slug' => 'survey-kepuasan-layanan',
            'description' => 'Bantu kami meningkatkan kualitas layanan pelatihan dan publikasi.',
            'welcome_message' => 'Isi survey ini kurang dari 3 menit. Jawaban Anda sangat berarti.',
            'thank_you_message' => 'Terima kasih! Feedback Anda telah terekam.',
            'is_active' => true,
            'require_login' => false,
            'allow_multiple_responses' => true,
            'show_progress' => true,
            'settings' => ['shuffle_questions' => false],
        ]);

        $questions = [
            [
                'type' => 'short_text',
                'question' => 'Nama lengkap Anda',
                'is_required' => true,
                'placeholder' => 'Tulis nama sesuai identitas',
            ],
            [
                'type' => 'choice_single',
                'question' => 'Bagaimana Anda mengenal BPVP Bantul?',
                'is_required' => true,
                'options' => [
                    ['label' => 'Media sosial'],
                    ['label' => 'Website BPVP'],
                    ['label' => 'Teman/keluarga'],
                    ['label' => 'Kampus/instansi'],
                ],
            ],
            [
                'type' => 'choice_multiple',
                'question' => 'Layanan apa saja yang pernah Anda gunakan?',
                'description' => 'Pilih lebih dari satu jika pernah mencoba beberapa.',
                'options' => [
                    ['label' => 'Pelatihan vokasi'],
                    ['label' => 'Sertifikasi'],
                    ['label' => 'Konsultasi karier'],
                    ['label' => 'Forum alumni'],
                ],
            ],
            [
                'type' => 'linear_scale',
                'question' => 'Seberapa puas dengan pengalaman Anda?',
                'is_required' => true,
                'settings' => ['min' => 1, 'max' => 5, 'left_label' => 'Kurang', 'right_label' => 'Sangat puas'],
            ],
            [
                'type' => 'long_text',
                'question' => 'Saran perbaikan apa yang paling penting menurut Anda?',
                'placeholder' => 'Tuliskan secara singkat',
            ],
        ];

        foreach ($questions as $index => $data) {
            $options = $data['options'] ?? [];
            unset($data['options']);

            /** @var SurveyQuestion $question */
            $question = $survey->questions()->create(array_merge($data, ['position' => $index]));

            foreach ($options as $optIndex => $option) {
                $question->options()->create([
                    'label' => $option['label'],
                    'value' => $option['value'] ?? null,
                    'position' => $optIndex,
                    'is_other' => $option['is_other'] ?? false,
                ]);
            }
        }
    }
}
