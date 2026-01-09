<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\SurveyResponsesExport;
use App\Models\Survey;
use App\Models\SurveyVersion;
use App\Models\SurveyQuestion;
use App\Models\SurveyQuestionOption;
use App\Models\SurveyCollaborator;
use App\Models\SurveyAnswer;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SurveyController extends Controller
{
    private const QUESTION_TYPES = [
        'short_text',
        'long_text',
        'choice_single',
        'choice_multiple',
        'dropdown',
        'linear_scale',
        'date',
        'time',
        'file_upload',
        'grid_single',
        'grid_multiple',
        'rating',
        'choice_single_other',
    ];

    public function __construct()
    {
        $this->middleware('permission:manage-surveys')->except('analytics');
        $this->middleware('permission:view-survey-analytics')->only('analytics');
    }

    public function index()
    {
        $user = auth()->user();
        $surveys = Survey::withCount('responses')
            ->when(! $user->can('manage-surveys'), function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhereHas('collaborators', fn($c) => $c->where('user_id', $user->id));
            })
            ->latest()
            ->paginate(15);

        return view('admin.surveys.index', compact('surveys'));
    }

    public function create()
    {
        $survey = new Survey([
            'is_active' => true,
            'allow_multiple_responses' => true,
            'show_progress' => true,
        ]);

        return view('admin.surveys.form', [
            'survey' => $survey,
            'questions' => collect(),
            'sections' => collect(),
            'skipRules' => collect(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateSurvey($request);
        $sections = $this->validateSectionsPayload($request->input('sections_payload'));
        $questions = $this->validateQuestionsPayload($request->input('questions_payload'), $sections);
        $skipRules = $this->validateSkipRulesPayload($request->input('skip_rules_payload'), $sections, $questions);

        DB::transaction(function () use ($data, $sections, $questions, $skipRules, &$survey) {
            $survey = Survey::create($data);
            $sectionMap = $this->syncSections($survey, $sections);
            $this->syncQuestions($survey, $questions, $sectionMap);
            $this->syncSkipRules($survey, $skipRules, $sectionMap);
            $this->storeVersion($survey, 'created');
        });

        return redirect()->route('admin.surveys.index')->with('success', 'Survey berhasil dibuat.');
    }

    public function edit(Survey $survey)
    {
        $this->authorizeSurvey($survey, 'editor');
        $survey->load(['sections.questions.options', 'skipRules', 'versions' => fn($q) => $q->latest()->take(5), 'collaborators.user', 'creator']);

        return view('admin.surveys.form', [
            'survey' => $survey,
            'questions' => $survey->questions,
            'sections' => $survey->sections,
            'skipRules' => $survey->skipRules,
        ]);
    }

    public function update(Request $request, Survey $survey)
    {
        $this->authorizeSurvey($survey, 'editor');
        $data = $this->validateSurvey($request, $survey);
        $sections = $this->validateSectionsPayload($request->input('sections_payload'));
        $questions = $this->validateQuestionsPayload($request->input('questions_payload'), $sections);
        $skipRules = $this->validateSkipRulesPayload($request->input('skip_rules_payload'), $sections, $questions);

        DB::transaction(function () use ($survey, $data, $sections, $questions, $skipRules) {
            $survey->update($data);
            $sectionMap = $this->syncSections($survey, $sections);
            $this->syncQuestions($survey, $questions, $sectionMap);
            $this->syncSkipRules($survey, $skipRules, $sectionMap);
            $this->storeVersion($survey, 'updated');
        });

        return redirect()->route('admin.surveys.index')->with('success', 'Survey diperbarui.');
    }

    public function destroy(Survey $survey)
    {
        $survey->delete();

        return redirect()->route('admin.surveys.index')->with('success', 'Survey dihapus.');
    }

    public function export(Survey $survey)
    {
        $this->authorizeSurvey($survey, 'viewer');
        $survey->load('questions');
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="survey-'.$survey->slug.'-responses.csv"',
        ];

        $callback = function () use ($survey) {
            $handle = fopen('php://output', 'w');
            $questionHeaders = $survey->questions->pluck('question')->toArray();
            fputcsv($handle, array_merge(['response_id', 'submitted_at', 'user'], $questionHeaders));
            $survey->responses()->with('answers')->chunk(200, function ($chunk) use ($handle, $survey) {
                foreach ($chunk as $response) {
                    $row = [
                        $response->id,
                        $response->submitted_at,
                        optional($response->user)->email ?? 'anon',
                    ];
                    foreach ($survey->questions as $question) {
                        $answer = $response->answers->firstWhere('survey_question_id', $question->id);
                        $row[] = $answer?->answer_text ?? $answer?->answer_numeric ?? ($answer?->answer_json ? json_encode($answer->answer_json) : '');
                    }
                    fputcsv($handle, $row);
                }
            });
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportXlsx(Survey $survey)
    {
        $this->authorizeSurvey($survey, 'viewer');
        return Excel::download(new SurveyResponsesExport($survey), 'survey-'.$survey->slug.'-responses.xlsx');
    }

    public function analytics(Survey $survey)
    {
        $this->authorizeSurvey($survey, 'viewer');
        $survey->load(['questions.options'])->loadCount('responses');

        $responsesCount = $survey->responses_count;
        $uniqueRespondents = $survey->responses()->whereNotNull('user_id')->distinct('user_id')->count('user_id');
        $anonymousResponses = $responsesCount - $uniqueRespondents;

        $start = request('start');
        $end = request('end');
        $dailyQuery = $survey->responses()->selectRaw('DATE(created_at) as date, COUNT(*) as total');
        if ($start) {
            $dailyQuery->whereDate('created_at', '>=', $start);
        }
        if ($end) {
            $dailyQuery->whereDate('created_at', '<=', $end);
        }
        $dailyResponses = $dailyQuery->groupBy('date')->orderBy('date')->get();

        $answersByQuestion = SurveyAnswer::whereIn('survey_question_id', $survey->questions->pluck('id'))
            ->get()
            ->groupBy('survey_question_id');

        $questionStats = $this->buildQuestionStats($survey, $answersByQuestion);

        return view('admin.surveys.analytics', [
            'survey' => $survey,
            'responsesCount' => $responsesCount,
            'uniqueRespondents' => $uniqueRespondents,
            'anonymousResponses' => $anonymousResponses,
            'dailyResponses' => $dailyResponses,
            'questionStats' => $questionStats,
        ]);
    }

    private function validateSurvey(Request $request, ?Survey $survey = null): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'welcome_message' => 'nullable|string',
            'thank_you_message' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'require_login' => 'nullable|boolean',
            'allow_multiple_responses' => 'nullable|boolean',
            'show_progress' => 'nullable|boolean',
            'max_responses' => 'nullable|integer|min:1',
            'opens_at' => 'nullable|date',
            'closes_at' => 'nullable|date|after_or_equal:opens_at',
            'questions_payload' => 'required|string',
            'sections_payload' => 'required|string',
            'skip_rules_payload' => 'nullable|string',
            'theme_primary' => 'nullable|string|max:20',
            'theme_font' => 'nullable|string|max:100',
            'theme_cover' => 'nullable|url',
            'restrict_to_logged_in' => 'nullable|boolean',
            'allow_embed' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['require_login'] = $request->boolean('require_login');
        $data['allow_multiple_responses'] = $request->boolean('allow_multiple_responses');
        $data['show_progress'] = $request->boolean('show_progress');
        $data['settings'] = [
            'shuffle_questions' => $request->boolean('shuffle_questions'),
        ];
        $data['theme'] = [
            'primary' => $request->input('theme_primary'),
            'font' => $request->input('theme_font'),
            'cover' => $request->input('theme_cover'),
        ];
        $data['restrict_to_logged_in'] = $request->boolean('restrict_to_logged_in');
        $data['allow_embed'] = $request->boolean('allow_embed', true);

        if ($survey) {
            unset($data['questions_payload']);
            unset($data['sections_payload'], $data['skip_rules_payload']);
        }

        return $data;
    }

    private function validateSectionsPayload(?string $payload): array
    {
        $decoded = json_decode($payload ?? '', true);
        if (! is_array($decoded) || ! count($decoded)) {
            throw ValidationException::withMessages(['sections_payload' => 'Minimal satu section diperlukan.']);
        }

        $sections = [];
        foreach ($decoded as $index => $item) {
            $validator = Validator::make($item ?? [], [
                'id' => 'nullable|string',
                'key' => 'required|string',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'position' => 'nullable|integer|min:0',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $data = $validator->validated();
            $data['position'] = $data['position'] ?? $index;
            $sections[] = $data;
        }

        return $sections;
    }

    private function validateQuestionsPayload(?string $payload, array $sections = []): array
    {
        $decoded = json_decode($payload ?? '', true);

        if (! is_array($decoded)) {
            throw ValidationException::withMessages(['questions_payload' => 'Struktur pertanyaan tidak valid. Ulangi simpan.']);
        }

        $sectionKeys = collect($sections)->pluck('key')->all();

        $questions = [];
        foreach ($decoded as $index => $item) {
            $validator = Validator::make($item ?? [], [
                'id' => 'nullable|string',
                'question' => 'required|string|max:1000',
                'description' => 'nullable|string',
                'type' => 'required|string|in:' . implode(',', self::QUESTION_TYPES),
                'is_required' => 'boolean',
                'placeholder' => 'nullable|string|max:255',
                'position' => 'nullable|integer|min:0',
                'settings.min' => 'nullable|integer',
                'settings.max' => 'nullable|integer',
                'settings.left_label' => 'nullable|string|max:100',
                'settings.right_label' => 'nullable|string|max:100',
                'settings.max_length' => 'nullable|integer|min:1|max:1000',
                'settings.max_size' => 'nullable|integer|min:1', // file upload MB
                'settings.mime' => 'nullable|string',
                'settings.min_choices' => 'nullable|integer|min:0',
                'settings.max_choices' => 'nullable|integer|min:0',
                'settings.rows' => 'nullable|array',
                'settings.columns' => 'nullable|array',
                'settings.rows.*' => 'nullable|string|max:255',
                'settings.columns.*' => 'nullable|string|max:255',
                'options' => 'array',
                'options.*.id' => 'nullable|string',
                'options.*.label' => 'required_with:options|string|max:255',
                'options.*.value' => 'nullable|string|max:255',
                'options.*.is_other' => 'boolean',
                'options.*.position' => 'nullable|integer|min:0',
                'section_key' => 'nullable|string',
                'validation.regex' => 'nullable|string',
                'validation.format' => 'nullable|string|in:email,phone',
                'visibility_rules' => 'nullable|array',
                'visibility_rules.*.question_id' => 'required_with:visibility_rules|string',
                'visibility_rules.*.action' => 'required_with:visibility_rules|string|in:show,hide',
                'visibility_rules.*.equals' => 'nullable|string',
                'visibility_rules.*.in' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $question = $validator->validated();
            $question['position'] = $question['position'] ?? $index;
            $question['options'] = $question['options'] ?? [];
            $question['is_required'] = (bool) ($question['is_required'] ?? false);
            if (! empty($sectionKeys) && (! isset($question['section_key']) || ! in_array($question['section_key'], $sectionKeys, true))) {
                $question['section_key'] = $sectionKeys[0];
            }

            if (in_array($question['type'], ['choice_single', 'choice_multiple', 'dropdown'], true) && count($question['options']) < 1) {
                throw ValidationException::withMessages(['questions_payload' => 'Pertanyaan pilihan ganda/daftar harus memiliki minimal 1 opsi.']);
            }

            if ($question['type'] === 'linear_scale') {
                $min = $question['settings']['min'] ?? 1;
                $max = $question['settings']['max'] ?? 5;
                if ($min >= $max) {
                    throw ValidationException::withMessages(['questions_payload' => 'Pengaturan skala harus memiliki rentang minimum < maksimum.']);
                }
                $question['settings']['min'] = $min;
                $question['settings']['max'] = $max;
            }

            if ($question['type'] === 'grid_multiple' || $question['type'] === 'grid_single') {
                if (empty($question['settings']['rows']) || empty($question['settings']['columns'])) {
                    throw ValidationException::withMessages(['questions_payload' => 'Pertanyaan grid wajib punya baris dan kolom.']);
                }
            }

            $questions[] = $question;
        }

        return $questions;
    }

    private function validateSkipRulesPayload(?string $payload, array $sections, array $questions): array
    {
        if (! $payload) {
            return [];
        }

        $decoded = json_decode($payload, true);
        if (! is_array($decoded)) {
            throw ValidationException::withMessages(['skip_rules_payload' => 'Format skip logic tidak valid.']);
        }

        $questionIds = collect($questions)->pluck('id')->filter()->all();
        $sectionKeys = collect($sections)->pluck('key', 'id');

        $rules = [];
        foreach ($decoded as $rule) {
            $validator = Validator::make($rule ?? [], [
                'question_id' => 'required|string',
                'target_section_key' => 'required|string',
                'conditions' => 'required|array',
                'conditions.selected_option_ids' => 'nullable|array',
                'conditions.equals_text' => 'nullable|string',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $data = $validator->validated();
            if ($questionIds && ! in_array($data['question_id'], $questionIds, true)) {
                continue;
            }
            $rules[] = $data;
        }

        return $rules;
    }

    private function syncSections(Survey $survey, array $sections): array
    {
        $existing = $survey->sections()->get()->keyBy('id');
        $kept = [];
        $map = [];

        foreach ($sections as $section) {
            $model = $section['id'] && $existing->has($section['id'])
                ? $existing->get($section['id'])
                : $survey->sections()->make();

            $model->fill([
                'title' => $section['title'],
                'description' => $section['description'] ?? null,
                'position' => $section['position'] ?? 0,
            ]);
            $model->save();
            $kept[] = $model->id;
            $map[$section['key']] = $model->id;
        }

        $survey->sections()->whereNotIn('id', $kept)->delete();

        return $map;
    }

    private function syncQuestions(Survey $survey, array $questions, array $sectionMap): void
    {
        $existingQuestions = $survey->questions()->with('options')->get()->keyBy('id');
        $keptQuestionIds = [];

        foreach ($questions as $questionData) {
            /** @var SurveyQuestion $question */
            $question = $questionData['id'] && $existingQuestions->has($questionData['id'])
                ? $existingQuestions->get($questionData['id'])
                : new SurveyQuestion(['survey_id' => $survey->id]);

            $question->fill([
                'type' => $questionData['type'],
                'question' => $questionData['question'],
                'description' => $questionData['description'] ?? null,
                'is_required' => $questionData['is_required'] ?? false,
                'position' => $questionData['position'] ?? 0,
                'settings' => $questionData['settings'] ?? [],
                'placeholder' => $questionData['placeholder'] ?? null,
                'survey_section_id' => $sectionMap[$questionData['section_key']] ?? null,
                'validation' => $questionData['validation'] ?? null,
                'visibility_rules' => $questionData['visibility_rules'] ?? [],
            ]);
            $question->save();

            $keptQuestionIds[] = $question->id;

            $existingOptions = $question->options()->get()->keyBy('id');
            $keptOptionIds = [];

            foreach ($questionData['options'] as $idx => $optionData) {
                /** @var SurveyQuestionOption $option */
                $optionId = $optionData['id'] ?? null;
                $option = $optionId && $existingOptions->has($optionId)
                    ? $existingOptions->get($optionId)
                    : new SurveyQuestionOption(['survey_question_id' => $question->id]);

                $option->fill([
                    'label' => $optionData['label'] ?? '',
                    'value' => $optionData['value'] ?? null,
                    'is_other' => $optionData['is_other'] ?? false,
                    'position' => $optionData['position'] ?? $idx,
                ]);
                $option->save();

                $keptOptionIds[] = $option->id;
            }

            $question->options()->whereNotIn('id', $keptOptionIds)->delete();
        }

        $survey->questions()->whereNotIn('id', $keptQuestionIds)->delete();
    }

    private function syncSkipRules(Survey $survey, array $rules, array $sectionMap): void
    {
        $existing = $survey->skipRules()->get()->keyBy('id');
        $surveyQuestionMap = $survey->questions()->pluck('id')->all();
        $kept = [];

        foreach ($rules as $rule) {
            if (! in_array($rule['question_id'], $surveyQuestionMap, true)) {
                continue;
            }
            $targetSectionId = $sectionMap[$rule['target_section_key']] ?? null;
            if (! $targetSectionId) {
                continue;
            }
            $model = $survey->skipRules()->make();
            $model->fill([
                'survey_id' => $survey->id,
                'survey_question_id' => $rule['question_id'],
                'target_section_id' => $targetSectionId,
                'conditions' => $rule['conditions'],
            ]);
            $model->save();
            $kept[] = $model->id;
        }

        $survey->skipRules()->whereNotIn('id', $kept)->delete();
    }

    private function buildQuestionStats(Survey $survey, Collection $answersByQuestion): array
    {
        $stats = [];

        foreach ($survey->questions as $question) {
            $answers = $answersByQuestion->get($question->id, collect());
            $stat = [
                'question' => $question,
                'responses' => $answers->count(),
            ];

            if (in_array($question->type, ['choice_single', 'choice_multiple', 'dropdown', 'choice_single_other'], true)) {
                $optionStats = [];
                foreach ($question->options as $option) {
                    $count = $answers->filter(function (SurveyAnswer $answer) use ($option) {
                        $selected = $answer->selected_option_ids ?? [];
                        return in_array($option->id, $selected, true);
                    })->count();

                    $optionStats[] = [
                        'label' => $option->label,
                        'count' => $count,
                    ];
                }
                $stat['option_stats'] = $optionStats;
            } elseif (in_array($question->type, ['linear_scale', 'rating'], true)) {
                $values = $answers->pluck('answer_numeric')->filter();
                $distribution = $values->countBy()->map(fn ($c, $value) => ['value' => $value, 'count' => $c])->values();
                $stat['scale'] = [
                    'avg' => $values->count() ? round($values->avg(), 2) : null,
                    'min' => $values->min(),
                    'max' => $values->max(),
                    'distribution' => $distribution,
                ];
            } else {
                $stat['samples'] = $answers->pluck('answer_text')->filter()->take(3)->values();
            }

            $stats[] = $stat;
        }

        return $stats;
    }

    public function duplicate(Survey $survey)
    {
        $this->authorizeSurvey($survey, 'editor');
        DB::transaction(function () use ($survey, &$newSurvey) {
            $newSurvey = $survey->replicate(['slug', 'embed_token', 'created_at', 'updated_at']);
            $newSurvey->title = $survey->title . ' (Copy)';
            $newSurvey->slug = $survey->slug . '-' . Str::random(4);
            $newSurvey->embed_token = Str::random(24);
            $newSurvey->save();

            $sectionMap = [];
            foreach ($survey->sections as $section) {
                $newSection = $section->replicate(['id', 'created_at', 'updated_at']);
                $newSection->survey_id = $newSurvey->id;
                $newSection->save();
                $sectionMap[$section->id] = $newSection->id;
            }

            $questionMap = [];
            foreach ($survey->questions as $question) {
                $newQuestion = $question->replicate(['id', 'created_at', 'updated_at']);
                $newQuestion->survey_id = $newSurvey->id;
                $newQuestion->survey_section_id = $sectionMap[$question->survey_section_id] ?? null;
                $newQuestion->save();
                $questionMap[$question->id] = $newQuestion->id;

                foreach ($question->options as $option) {
                    $newOption = $option->replicate(['id', 'created_at', 'updated_at']);
                    $newOption->survey_question_id = $newQuestion->id;
                    $newOption->save();
                }
            }

            foreach ($survey->skipRules as $rule) {
                $newSurvey->skipRules()->create([
                    'survey_id' => $newSurvey->id,
                    'survey_question_id' => $questionMap[$rule->survey_question_id] ?? null,
                    'target_section_id' => $sectionMap[$rule->target_section_id] ?? null,
                    'conditions' => $rule->conditions,
                ]);
            }

            $this->storeVersion($newSurvey, 'duplicate');
        });

        return redirect()->route('admin.surveys.edit', $newSurvey)->with('success', 'Survey berhasil diduplikasi.');
    }

    public function restoreVersion(Survey $survey, SurveyVersion $version)
    {
        $this->authorizeSurvey($survey, 'editor');
        $snapshot = $version->snapshot;
        if (! $snapshot) {
            return back()->with('error', 'Snapshot tidak ditemukan.');
        }

        DB::transaction(function () use ($survey, $snapshot) {
            $sections = $snapshot['sections'] ?? [];
            $questions = $snapshot['questions'] ?? [];
            $skipRules = $snapshot['skip_rules'] ?? [];
            $sectionMap = $this->syncSections($survey, $sections);
            $this->syncQuestions($survey, $questions, $sectionMap);
            $this->syncSkipRules($survey, $skipRules, $sectionMap);
        });

        return redirect()->route('admin.surveys.edit', $survey)->with('success', 'Survey dipulihkan ke versi sebelumnya.');
    }

    private function storeVersion(Survey $survey, string $note = null): void
    {
        $survey->load(['sections', 'questions.options', 'skipRules']);
        $snapshot = [
            'sections' => $survey->sections->map(fn($s) => [
                'id' => $s->id,
                'key' => $s->id,
                'title' => $s->title,
                'description' => $s->description,
                'position' => $s->position,
            ])->values()->toArray(),
            'questions' => $survey->questions->map(function ($q) {
                return [
                    'id' => $q->id,
                    'question' => $q->question,
                    'description' => $q->description,
                    'type' => $q->type,
                    'is_required' => $q->is_required,
                    'placeholder' => $q->placeholder,
                    'position' => $q->position,
                    'settings' => $q->settings,
                    'validation' => $q->validation,
                    'section_key' => $q->survey_section_id,
                    'options' => $q->options->map(fn($o) => [
                        'id' => $o->id,
                        'label' => $o->label,
                        'value' => $o->value,
                        'position' => $o->position,
                        'is_other' => $o->is_other,
                    ])->values()->toArray(),
                ];
            })->values()->toArray(),
            'skip_rules' => $survey->skipRules->map(fn($r) => [
                'question_id' => $r->survey_question_id,
                'target_section_key' => $r->target_section_id,
                'conditions' => $r->conditions,
            ])->values()->toArray(),
        ];

        $survey->versions()->create([
            'user_id' => auth()->id(),
            'snapshot' => $snapshot,
            'note' => $note,
        ]);
    }

    public function addCollaborator(Request $request, Survey $survey)
    {
        $this->authorizeSurvey($survey, 'owner');
        $data = $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:owner,editor,viewer',
        ]);
        $user = User::where('email', $data['email'])->first();
        if (! $user) {
            return back()->with('error', 'Pengguna tidak ditemukan.');
        }
        SurveyCollaborator::updateOrCreate(
            ['survey_id' => $survey->id, 'user_id' => $user->id],
            ['role' => $data['role']]
        );

        return back()->with('success', 'Kolaborator ditambahkan.');
    }

    public function removeCollaborator(Survey $survey, SurveyCollaborator $collaborator)
    {
        $this->authorizeSurvey($survey, 'owner');
        if ($collaborator->survey_id !== $survey->id) {
            abort(404);
        }
        $collaborator->delete();

        return back()->with('success', 'Kolaborator dihapus.');
    }

    private function authorizeSurvey(Survey $survey, string $neededRole = 'viewer'): void
    {
        $user = auth()->user();
        if ($user->can('manage-surveys')) {
            return;
        }

        $roleRank = ['viewer' => 1, 'editor' => 2, 'owner' => 3];
        $userRoleRank = 0;

        if ($survey->created_by === $user->id) {
            $userRoleRank = $roleRank['owner'];
        } else {
            $collab = $survey->collaborators()->where('user_id', $user->id)->first();
            if ($collab) {
                $userRoleRank = $roleRank[$collab->role] ?? 0;
            }
        }

        if ($userRoleRank >= ($roleRank[$neededRole] ?? 0)) {
            return;
        }

        abort(403, 'Anda tidak memiliki akses ke survey ini.');
    }

    public function downloadAttachment(SurveyAnswer $answer)
    {
        $answer->loadMissing('response.survey');
        if (! $answer->response || ! $answer->response->survey) {
            abort(404);
        }

        $this->authorizeSurvey($answer->response->survey, 'viewer');

        if (! $answer->file_path) {
            abort(404);
        }

        $path = $answer->file_path;
        $disk = null;

        if (Storage::exists($path)) {
            $disk = config('filesystems.default', 'local');
        } elseif (Storage::disk('public')->exists($path)) {
            $disk = 'public';
        } else {
            abort(404);
        }

        $name = basename($path);
        return Storage::disk($disk)->download($path, $name);
    }
}
