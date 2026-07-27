<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UploadProofRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'payment_proof' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120', // 5MB
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'payment_proof.required' => 'Bukti pembayaran wajib diupload.',
            'payment_proof.image'    => 'File harus berupa gambar.',
            'payment_proof.mimes'    => 'Format file: JPG, JPEG, PNG, atau WEBP.',
            'payment_proof.max'      => 'Ukuran file maksimal 5MB.',
        ];
    }
}
