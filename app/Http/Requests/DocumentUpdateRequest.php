<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('documents.edit');
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'folder' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'is_public' => ['boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:document_tags,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('Document title is required'),
        ];
    }
}
