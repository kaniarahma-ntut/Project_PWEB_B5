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
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }
}