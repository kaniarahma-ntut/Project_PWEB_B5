<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        // Ubah jadi true agar request diizinkan.
        // (Keamanan role admin/pustakawan sudah kita tangani di routes/web.php)
        return true;
    }

    /**
     * Aturan validasi untuk data yang dikirim.
     */
    public function rules(): array
    {
        return [
            'judul'     => ['required', 'string', 'max:255'],
            'penulis'   => ['required', 'string', 'max:255'],
            'ISBN'      => ['nullable', 'string', 'max:20', 'unique:books,ISBN'],
            'kategori'  => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'cover'     => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // Max ukuran 2MB
        ];
    }

    /**
     * Kustomisasi pesan error (Opsional, agar lebih ramah dibaca).
     */
    public function messages(): array
    {
        return [
            'judul.required'    => 'Judul buku wajib diisi.',
            'penulis.required'  => 'Nama penulis wajib diisi.',
            'kategori.required' => 'Kategori buku wajib diisi.',
            'ISBN.unique'       => 'Nomor ISBN ini sudah terdaftar pada buku lain.',
            'cover.image'       => 'File cover harus berupa gambar.',
            'cover.max'         => 'Ukuran file cover maksimal adalah 2MB.',
            'cover.mimes'       => 'Format cover harus berupa jpeg, png, jpg, atau webp.',
        ];
    }
}
