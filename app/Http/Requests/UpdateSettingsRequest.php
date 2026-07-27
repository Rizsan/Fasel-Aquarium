<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
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
            'timezone' => ['required', 'string', 'timezone'],
            'date_format' => ['required', 'string', 'in:d/m/Y,m/d/Y,Y-m-d'],
            'products_per_page' => ['required', 'integer', 'min:1', 'max:100'],
            'maintenance_mode' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'timezone.required' => 'Timezone wajib diisi.',
            'timezone.timezone' => 'Timezone tidak valid.',
            'date_format.required' => 'Format tanggal wajib diisi.',
            'date_format.in' => 'Format tanggal tidak didukung.',
            'products_per_page.required' => 'Jumlah produk per halaman wajib diisi.',
            'products_per_page.min' => 'Jumlah produk minimal 1.',
            'products_per_page.max' => 'Jumlah produk maksimal 100.',
        ];
    }
}
