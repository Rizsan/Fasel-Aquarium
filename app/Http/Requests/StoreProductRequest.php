<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'description' => ['nullable', 'string', 'max:2000'],
            'stock'       => ['nullable', 'integer', 'min:0'],
            'is_active'   => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Nama produk wajib diisi.',
            'name.max'       => 'Nama produk maksimal 255 karakter.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric'  => 'Harga harus berupa angka.',
            'price.min'      => 'Harga tidak boleh negatif.',
            'image.image'    => 'File harus berupa gambar.',
            'image.mimes'    => 'Format gambar harus JPG, JPEG, atau PNG.',
            'image.max'      => 'Ukuran gambar maksimal 2MB.',
            'stock.integer'  => 'Stok harus berupa bilangan bulat.',
            'stock.min'      => 'Stok tidak boleh negatif.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'stock'     => $this->input('stock', 0),
        ]);
    }
}
