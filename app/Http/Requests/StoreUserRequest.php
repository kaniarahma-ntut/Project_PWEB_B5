<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Menentukan apakah request ini boleh dijalankan.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validasi saat membuat akun baru.
     */
    public function rules(): array
    {
        return [
            'role' => ['required', 'in:admin,pustakawan,anggota'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'foto_profil' => ['nullable', 'image', 'max:2048'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}