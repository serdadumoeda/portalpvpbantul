<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlumniTracerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'national_id' => 'nullable|string|max:32',
            'alumni_number' => 'required|string|max:40|unique:alumni_tracers,alumni_number',
            'program_id' => 'nullable|exists:programs,id',
            'program_name' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:2000|max:' . (now()->year + 1),
            'training_batch' => 'nullable|string|max:100',
            'status' => 'required|in:employed,entrepreneur,studying,seeking,other',
            'job_title' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'industry_sector' => 'nullable|string|max:255',
            'job_start_date' => 'nullable|date',
            'employment_type' => 'nullable|string|max:120',
            'salary_range' => 'nullable|string|max:120',
            'continue_study' => 'sometimes|boolean',
            'is_entrepreneur' => 'sometimes|boolean',
            'business_name' => 'nullable|string|max:255',
            'business_sector' => 'nullable|string|max:255',
            'satisfaction_rating' => 'nullable|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:2000',
            'consent_given' => 'accepted',
        ];
    }

    public function messages(): array
    {
        return [
            'alumni_number.unique' => 'Nomor alumni ini sudah tercatat, silakan hubungi admin jika perlu koreksi.',
        ];
    }
}
