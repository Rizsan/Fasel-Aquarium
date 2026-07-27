<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIdentityRequest extends FormRequest
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
            'app_name' => ['required', 'string', 'max:255'],
            'slogan' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,ico', 'max:512'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'app_name.required' => 'Nama aplikasi wajib diisi.',
            'app_name.max' => 'Nama aplikasi maksimal 255 karakter.',
            'logo.image' => 'Logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus format: jpeg, png, jpg, atau gif.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'favicon.image' => 'Favicon harus berupa gambar.',
            'favicon.mimes' => 'Favicon harus format: jpeg, png, jpg, gif, atau ico.',
            'favicon.max' => 'Ukuran favicon maksimal 512KB.',
        ];
    }
}
