<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadGalleryRequest extends FormRequest
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
            'gallery' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'gallery.required' => 'File galeri wajib diunggah.',
            'gallery.image' => 'File harus berupa gambar.',
            'gallery.mimes' => 'Galeri harus format: jpeg, png, jpg, atau gif.',
            'gallery.max' => 'Ukuran galeri maksimal 5MB.',
        ];
    }
}
