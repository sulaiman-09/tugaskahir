<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CareerStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:careers,slug'],
            'description' => ['nullable', 'string'],
            'image_path' => ['nullable', 'string', 'max:500'],
            'job_requirements' => ['nullable', 'array'],
            'job_requirements.*' => ['nullable', 'string'],
            'job_description' => ['nullable', 'array'],
            'job_description.*' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:fulltime,contract,internship'],
            'education_level' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
