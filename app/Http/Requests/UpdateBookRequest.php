<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'judul'     => ['required', 'string', 'max:255'],
            'penulis'   => ['required', 'string', 'max:255'],
            'ISBN'      => ['nullable', 'string', 'max:50'],
            'kategori'  => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'cover'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required'    => 'Judul buku wajib diisi.',
            'penulis.required'  => 'Nama penulis wajib diisi.',
            'kategori.required' => 'Kategori buku wajib diisi.',
            'cover.image'       => 'File cover harus berupa gambar.',
            'cover.max'         => 'Ukuran file cover maksimal adalah 2MB.',
        ];
    }
}
