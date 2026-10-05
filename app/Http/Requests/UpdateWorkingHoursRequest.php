<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkingHoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'days' => ['required', 'array'],
            'days.*.is_active' => ['sometimes', 'boolean'],
            'days.*.start_time' => ['required_with:days.*.is_active', 'nullable', 'date_format:H:i'],
            'days.*.end_time' => ['required_with:days.*.is_active', 'nullable', 'date_format:H:i', 'after:days.*.start_time'],
            'days.*.break_start' => ['nullable', 'date_format:H:i'],
            'days.*.break_end' => ['nullable', 'date_format:H:i', 'after:days.*.break_start'],
        ];
    }
}
