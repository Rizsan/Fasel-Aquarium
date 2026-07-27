<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAboutRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'about_content' => ['required', 'string', 'max:5000'],
            'why_choose_us' => ['nullable', 'string', 'max:2000'],
            'how_to_shop' => ['nullable', 'string', 'max:2000'],
            'facilities' => ['nullable', 'string', 'max:2000'],
            'contact_address' => ['nullable', 'string', 'max:500'],
            'contact_whatsapp' => ['nullable', 'string', 'max:20'],
            'contact_instagram' => ['nullable', 'string', 'max:100'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'operation_hours' => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul wajib diisi.',
            'about_content.required' => 'Isi tentang kami wajib diisi.',
            'about_content.max' => 'Isi tentang kami maksimal 5000 karakter.',
        ];
    }
}
