<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('documents.create');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['required', 'file', 'max:51200'], // 50MB max
            'folder' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'is_public' => ['boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:document_tags,id'],
            'branch_id' => ['nullable', 'exists:branches,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('Document title is required'),
            'file.required' => __('Please select a file to upload'),
            'file.max' => __('File size must not exceed 50MB'),
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('branch_id') && $this->user()->branch_id) {
            $this->merge([
                'branch_id' => $this->user()->branch_id,
            ]);
        }

        if (!$this->has('is_public')) {
            $this->merge([
                'is_public' => false,
            ]);
        }
    }
}
