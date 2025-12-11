<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandingKpiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $uniqueRule = Rule::unique('branding_kpi_reports', 'month')
            ->where(fn ($query) => $query->where('year', $this->input('year')));

        if ($this->route('branding_kpi')) {
            $uniqueRule = $uniqueRule->ignore($this->route('branding_kpi')->id);
        }

        return [
            'month' => ['required', 'integer', 'between:1,12', $uniqueRule],
            'year' => ['required', 'integer', 'min:2020', 'max:' . (now()->year + 1)],
            'reach_previous' => 'nullable|integer|min:0',
            'reach_current' => 'nullable|integer|min:0',
            'reach_target' => 'nullable|integer|min:0',
            'reach_notes' => 'nullable|string|max:255',
            'registrant_previous' => 'nullable|integer|min:0',
            'registrant_current' => 'nullable|integer|min:0',
            'registrant_target' => 'nullable|integer|min:0',
            'registrant_notes' => 'nullable|string|max:255',
            'rating_previous' => 'nullable|numeric|min:0|max:5',
            'rating_current' => 'nullable|numeric|min:0|max:5',
            'rating_target' => 'nullable|numeric|min:0|max:5',
            'rating_notes' => 'nullable|string|max:255',
            'partner_previous' => 'nullable|integer|min:0',
            'partner_current' => 'nullable|integer|min:0',
            'partner_target' => 'nullable|integer|min:0',
            'partner_notes' => 'nullable|string|max:255',
            'low_interest_program' => 'nullable|string|max:255',
            'issue_description' => 'nullable|string',
            'action_plan' => 'nullable|string',
            'reported_by' => 'nullable|string|max:120',
            'approved_by' => 'nullable|string|max:120',
            'meeting_date' => 'nullable|date',
            'evidence_link' => 'nullable|url',
            'meeting_notes' => 'nullable|string',
            'low_interest_programs' => 'nullable|array',
            'low_interest_programs.*' => 'nullable|string|max:255',
            'low_interest_issues' => 'nullable|array',
            'low_interest_issues.*' => 'nullable|string',
            'low_interest_actions' => 'nullable|array',
            'low_interest_actions.*' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'month.unique' => 'Laporan untuk bulan dan tahun tersebut sudah ada. Silakan gunakan menu edit.',
        ];
    }
}
