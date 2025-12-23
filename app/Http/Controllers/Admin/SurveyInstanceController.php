<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyInstance;
use App\Models\CourseClass;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SurveyInstanceController extends Controller
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function index()
    {
        $statusOptions = SurveyInstance::statuses();
        $statusFilter = request('status');
        $surveyFilter = request('survey_id');
        $classFilter = request('class_id');

        $query = SurveyInstance::with(['survey', 'course', 'instructor'])->orderByDesc('created_at');

        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }
        if ($surveyFilter) {
            $query->where('survey_id', $surveyFilter);
        }
        if ($classFilter) {
            $query->where('course_class_id', $classFilter);
        }

        $instances = $query->paginate(20)->withQueryString();
        $surveys = Survey::orderBy('title')->pluck('title', 'id');
        $classes = CourseClass::orderBy('title')->pluck('title', 'id');

        return view('admin.survey_instance.index', compact('instances', 'statusOptions', 'statusFilter', 'surveys', 'surveyFilter', 'classes', 'classFilter'));
    }

    public function dashboard()
    {
        $statusOptions = SurveyInstance::statuses();
        $statusFilter = request('status');
        $surveyFilter = request('survey_id');

        $query = SurveyInstance::with(['survey', 'course'])
            ->withCount('responses')
            ->orderByDesc('created_at');

        if ($statusFilter && array_key_exists($statusFilter, $statusOptions)) {
            $query->where('status', $statusFilter);
        }
        if ($surveyFilter) {
            $query->where('survey_id', $surveyFilter);
        }

        $instances = $query->paginate(15)->withQueryString();
        $surveys = Survey::orderBy('title')->pluck('title', 'id');

        $totals = [
            'instances' => $instances->total(),
            'responses' => $instances->sum('responses_count'),
            'avg_numeric' => SurveyAnswer::whereHas('response', function ($q) use ($instances) {
                    $q->whereIn('survey_instance_id', $instances->pluck('id'));
                })->avg('answer_numeric'),
            'with_threshold' => $instances->filter(function ($inst) {
                return ($inst->responses_count ?? 0) >= ($inst->min_responses_threshold ?? 0);
            })->count(),
        ];

        foreach ($instances as $inst) {
            $inst->avg_numeric = SurveyAnswer::whereHas('response', function ($q) use ($inst) {
                $q->where('survey_instance_id', $inst->id);
            })->avg('answer_numeric');
        }

        return view('admin.survey_instance.dashboard', compact(
            'instances',
            'statusOptions',
            'statusFilter',
            'surveys',
            'surveyFilter',
            'totals'
        ));
    }

    public function create()
    {
        $surveys = Survey::orderBy('title')->pluck('title', 'id');
        $classes = CourseClass::orderBy('title')->pluck('title', 'id');

        return view('admin.survey_instance.form', [
            'instance' => new SurveyInstance(['status' => 'draft', 'min_responses_threshold' => 5]),
            'surveys' => $surveys,
            'classes' => $classes,
            'action' => route('admin.survey-instance.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $instance = SurveyInstance::create($data);

        $this->logger->log(
            $request->user(),
            'survey.instance.created',
            "Survey instance '{$instance->id}' dibuat",
            $instance
        );

        return redirect()->route('admin.survey-instance.index')->with('success', 'Survey instance dibuat.');
    }

    public function edit(SurveyInstance $survey_instance)
    {
        $surveys = Survey::orderBy('title')->pluck('title', 'id');
        $classes = CourseClass::orderBy('title')->pluck('title', 'id');

        return view('admin.survey_instance.form', [
            'instance' => $survey_instance,
            'surveys' => $surveys,
            'classes' => $classes,
            'action' => route('admin.survey-instance.update', $survey_instance->id),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, SurveyInstance $survey_instance)
    {
        $data = $this->validateData($request);
        $survey_instance->update($data);

        $this->logger->log(
            $request->user(),
            'survey.instance.updated',
            "Survey instance '{$survey_instance->id}' diperbarui",
            $survey_instance
        );

        return redirect()->route('admin.survey-instance.index')->with('success', 'Survey instance diperbarui.');
    }

    public function destroy(SurveyInstance $survey_instance)
    {
        $this->logger->log(
            request()->user(),
            'survey.instance.deleted',
            "Survey instance '{$survey_instance->id}' dihapus",
            $survey_instance
        );
        $survey_instance->delete();

        return redirect()->route('admin.survey-instance.index')->with('success', 'Survey instance dihapus.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'survey_id' => 'required|exists:surveys,id',
            'course_class_id' => 'nullable|exists:course_classes,id',
            'instructor_id' => 'nullable|exists:users,id',
            'status' => 'required|in:' . implode(',', array_keys(SurveyInstance::statuses())),
            'opens_at' => 'nullable|date',
            'closes_at' => 'nullable|date|after_or_equal:opens_at',
            'min_responses_threshold' => 'nullable|integer|min:1|max:1000',
        ]);

        $data['min_responses_threshold'] = $data['min_responses_threshold'] ?? 5;
        return $data;
    }

    public function report(Request $request, SurveyInstance $survey_instance)
    {
        $survey_instance->load(['survey.questions.options']);
        $minResponses = $survey_instance->min_responses_threshold ?? 0;
        $from = $request->input('submitted_from');
        $to = $request->input('submitted_to');

        $responsesQuery = SurveyResponse::with('user')
            ->where('survey_instance_id', $survey_instance->id);
        $this->applyDateFilter($responsesQuery, $from, $to);

        $totalResponses = $responsesQuery->count();
        $canShowAnalytics = $totalResponses >= $minResponses;

        $avgNumeric = null;
        $topWords = collect();
        $questionStats = collect();

        if ($survey_instance->survey?->questions) {
            $questionStats = $this->buildQuestionStats($survey_instance, $from, $to, $canShowAnalytics);
            if ($canShowAnalytics) {
                $allAnswers = $this->filteredAnswers($survey_instance, $from, $to);
                $avgNumeric = $allAnswers->pluck('answer_numeric')
                    ->filter(fn ($v) => $v !== null)
                    ->avg();

                $topWords = $allAnswers
                    ->pluck('answer_text')
                    ->filter()
                    ->flatMap(function ($text) {
                        $tokens = preg_split('/\s+/', strtolower(strip_tags($text)));
                        return collect($tokens)->filter(fn ($t) => strlen($t) > 3);
                    })
                    ->countBy()
                    ->sortDesc()
                    ->take(10);
            }
        }

        $responses = SurveyResponse::with('user')
            ->where('survey_instance_id', $survey_instance->id)
            ->when($from, fn ($q) => $q->where('submitted_at', '>=', Carbon::parse($from)->startOfDay()))
            ->when($to, fn ($q) => $q->where('submitted_at', '<=', Carbon::parse($to)->endOfDay()))
            ->latest('submitted_at')
            ->take(20)
            ->get();

        return view('admin.survey_instance.report', [
            'survey_instance' => $survey_instance,
            'totalResponses' => $totalResponses,
            'avgNumeric' => $avgNumeric,
            'topWords' => $topWords,
            'responses' => $responses,
            'canShowAnalytics' => $canShowAnalytics,
            'questionStats' => $questionStats,
            'minResponses' => $minResponses,
            'submittedFrom' => $from,
            'submittedTo' => $to,
        ]);
    }

    private function summarizeQuestion(SurveyQuestion $question, \Illuminate\Support\Collection $answers): array
    {
        $total = $answers->count();
        $base = [
            'question' => $question,
            'type' => $question->type,
            'total' => $total,
        ];

        $choiceTypes = ['choice_single', 'dropdown', 'choice_single_other', 'choice_multiple'];
        $scaleTypes = ['linear_scale', 'rating'];
        $textTypes = ['short_text', 'long_text'];

        if (in_array($question->type, $choiceTypes)) {
            $optionLabels = $question->options->mapWithKeys(fn ($opt) => [$opt->id => $opt->label]);
            $distribution = $optionLabels->map(fn () => 0)->all();
            $otherCount = 0;

            foreach ($answers as $answer) {
                $selected = (array) ($answer->selected_option_ids ?? []);
                if (empty($selected) && $answer->answer_text) {
                    $otherCount++;
                    continue;
                }
                foreach ($selected as $optionId) {
                    if (isset($distribution[$optionId])) {
                        $distribution[$optionId]++;
                    } else {
                        $otherCount++;
                    }
                }
            }

            $distributionList = collect($distribution)->map(function ($count, $id) use ($optionLabels, $total) {
                $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                return [
                    'label' => $optionLabels[$id] ?? 'Opsi',
                    'count' => $count,
                    'percent' => $percent,
                ];
            })->values()->all();

            return array_merge($base, [
                'distribution' => $distributionList,
                'other_count' => $otherCount,
            ]);
        }

        if (in_array($question->type, $scaleTypes)) {
            $values = $answers->pluck('answer_numeric')->filter(fn ($v) => $v !== null)->values();
            return array_merge($base, [
                'scale' => [
                    'avg' => $values->avg(),
                    'min' => $values->min(),
                    'max' => $values->max(),
                    'count' => $values->count(),
                ],
            ]);
        }

        if (in_array($question->type, $textTypes)) {
            $topWords = $answers->pluck('answer_text')
                ->filter()
                ->flatMap(function ($text) {
                    $tokens = preg_split('/\s+/', strtolower(strip_tags($text)));
                    return collect($tokens)->filter(fn ($t) => strlen($t) > 3);
                })
                ->countBy()
                ->sortDesc()
                ->take(8);

            return array_merge($base, [
                'top_words' => $topWords,
            ]);
        }

        return $base;
    }

    public function exportResponses(Request $request, SurveyInstance $survey_instance)
    {
        $survey_instance->load(['survey', 'course', 'instructor']);
        $from = $request->input('submitted_from');
        $to = $request->input('submitted_to');

        $filename = 'survey-instance-' . ($survey_instance->id) . '-responses.csv';

        $callback = function () use ($survey_instance, $from, $to) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'response_id',
                'user_id',
                'user_name',
                'class_title',
                'instructor_name',
                'submitted_at',
                'question',
                'question_type',
                'answer_text',
                'answer_numeric',
                'selected_option_ids',
                'answer_json',
            ]);

            SurveyResponse::with(['user', 'course', 'instructor', 'answers.question'])
                ->where('survey_instance_id', $survey_instance->id)
                ->when($from, fn ($q) => $q->where('submitted_at', '>=', Carbon::parse($from)->startOfDay()))
                ->when($to, fn ($q) => $q->where('submitted_at', '<=', Carbon::parse($to)->endOfDay()))
                ->chunk(100, function ($responses) use ($out) {
                    foreach ($responses as $resp) {
                        foreach ($resp->answers as $answer) {
                            fputcsv($out, [
                                $resp->id,
                                $resp->user_id,
                                $resp->user->name ?? '',
                                $resp->course->title ?? '',
                                $resp->instructor->name ?? '',
                                $resp->submitted_at,
                                $answer->question->question ?? '',
                                $answer->question->type ?? '',
                                $answer->answer_text,
                                $answer->answer_numeric,
                                $answer->selected_option_ids ? implode('|', (array) $answer->selected_option_ids) : '',
                                $answer->answer_json ? json_encode($answer->answer_json) : '',
                            ]);
                        }
                    }
                });

            fclose($out);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportAggregates(Request $request, SurveyInstance $survey_instance)
    {
        $survey_instance->load(['survey.questions.options']);
        $from = $request->input('submitted_from');
        $to = $request->input('submitted_to');

        $stats = $this->buildQuestionStats($survey_instance, $from, $to, true);
        $filename = 'survey-instance-' . $survey_instance->id . '-aggregates.csv';

        $callback = function () use ($stats) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['question', 'type', 'metric', 'label', 'value', 'percent']);

            foreach ($stats as $stat) {
                $question = $stat['question']->question ?? '';
                $type = $stat['type'];
                $total = $stat['total'] ?? 0;
                fputcsv($out, [$question, $type, 'total_responses', '', $total, '']);

                if (! empty($stat['distribution'])) {
                    foreach ($stat['distribution'] as $row) {
                        fputcsv($out, [$question, $type, 'option', $row['label'], $row['count'], $row['percent'] ?? '']);
                    }
                    if (($stat['other_count'] ?? 0) > 0) {
                        fputcsv($out, [$question, $type, 'option_other', 'Lainnya', $stat['other_count'], '']);
                    }
                } elseif (isset($stat['scale'])) {
                    fputcsv($out, [$question, $type, 'avg', '', $stat['scale']['avg'], '']);
                    fputcsv($out, [$question, $type, 'min', '', $stat['scale']['min'], '']);
                    fputcsv($out, [$question, $type, 'max', '', $stat['scale']['max'], '']);
                    fputcsv($out, [$question, $type, 'count', '', $stat['scale']['count'], '']);
                } elseif (! empty($stat['top_words'])) {
                    foreach ($stat['top_words'] as $word => $count) {
                        fputcsv($out, [$question, $type, 'top_word', $word, $count, '']);
                    }
                }
            }

            fclose($out);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function buildQuestionStats(SurveyInstance $surveyInstance, ?string $from, ?string $to, bool $allowEmpty = false): \Illuminate\Support\Collection
    {
        if (! $surveyInstance->survey?->questions) {
            return collect();
        }

        $answerGroups = $this->filteredAnswers($surveyInstance, $from, $to)
            ->groupBy('survey_question_id');

        if ($answerGroups->isEmpty() && ! $allowEmpty) {
            return collect();
        }

        return $surveyInstance->survey->questions->mapWithKeys(function (SurveyQuestion $question) use ($answerGroups) {
            $answers = $answerGroups->get($question->id, collect());
            return [$question->id => $this->summarizeQuestion($question, $answers)];
        });
    }

    private function filteredAnswers(SurveyInstance $surveyInstance, ?string $from, ?string $to): \Illuminate\Support\Collection
    {
        return SurveyAnswer::with('question.options')
            ->whereHas('response', function ($q) use ($surveyInstance, $from, $to) {
                $q->where('survey_instance_id', $surveyInstance->id);
                $this->applyDateFilter($q, $from, $to);
            })
            ->get();
    }

    private function applyDateFilter($query, ?string $from, ?string $to): void
    {
        if ($from) {
            $query->where('submitted_at', '>=', Carbon::parse($from)->startOfDay());
        }
        if ($to) {
            $query->where('submitted_at', '<=', Carbon::parse($to)->endOfDay());
        }
    }
}
