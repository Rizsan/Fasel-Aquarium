<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RestoreDatabaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
{
    return auth()->check()
        && auth()->user()->role === 'admin';
}

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'database_file' => ['required', 'file', 'mimes:sql,zip', 'max:52428800'], // 50MB
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'database_file.required' => 'File database wajib diunggah.',
            'database_file.file' => 'Input harus berupa file.',
            'database_file.mimes' => 'File harus format: sql atau zip.',
            'database_file.max' => 'Ukuran file maksimal 50MB.',
        ];
    }
}
