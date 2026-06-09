<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Menentukan apakah request ini boleh dijalankan.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validasi saat mengubah data akun.
     */
    public function rules(): array
    {
        $userId = $this->route('user');

        return [
            'role' => ['nullable', 'in:admin,pustakawan,anggota'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],

            'nama_lengkap' => ['required', 'string', 'max:255'],
            'foto_profil' => ['nullable', 'image', 'max:2048'],
            'alamat' => ['nullable', 'string'],

            // Nomor HP boleh kosong, tapi kalau diisi harus diawali 08 dan 10-13 digit angka
            'no_hp' => ['nullable', 'string', 'regex:/^08[0-9]{8,11}$/'],

            // Password boleh kosong saat edit
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }

    /**
     * Pesan validasi custom.
     */
    public function messages(): array
    {
        return [
            'role.in' => 'Role yang dipilih tidak valid.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',

            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 255 karakter.',

            'foto_profil.image' => 'File foto profil harus berupa gambar.',
            'foto_profil.max' => 'Ukuran foto profil maksimal 2MB.',

            'no_hp.regex' => 'Nomor HP harus diawali 08 dan berisi 10 sampai 13 digit angka.',

            'password.min' => 'Password minimal harus 8 karakter.',
        ];
    }
}