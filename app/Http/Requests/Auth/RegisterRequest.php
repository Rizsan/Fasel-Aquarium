<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name'  => ['required', 'string', 'max:50'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'   => ['required', 'string', 'min:8', 'confirmed'],
            'terms'      => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Nama depan wajib diisi.',
            'first_name.max'      => 'Nama depan maksimal 50 karakter.',
            'last_name.required'  => 'Nama belakang wajib diisi.',
            'last_name.max'       => 'Nama belakang maksimal 50 karakter.',
            'email.required'      => 'Alamat email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'terms.accepted'      => 'Anda harus menyetujui syarat dan ketentuan.',
        ];
    }
}
