<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel users: menyimpan semua akun (admin, pustakawan, anggota).
     * Menggunakan SoftDeletes agar akun yang dinonaktifkan tidak hilang dari DB.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Role menentukan hak akses di seluruh sistem
            $table->enum('role', ['admin', 'pustakawan', 'anggota'])->default('anggota');

            $table->string('email')->unique();
            $table->string('nama_lengkap');
            $table->string('foto_profil')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('no_hp', 20)->nullable();

            // google_id digunakan untuk autentikasi via Google OAuth (Laravel Socialite)
            $table->string('google_id')->nullable()->unique();

            // password nullable karena login bisa hanya via Google
            $table->string('password')->nullable();

            $table->rememberToken();

            // SoftDeletes: menambahkan kolom deleted_at
            // Akun yang "dihapus" masih ada di DB (bisa dipulihkan)
            $table->softDeletes();

            $table->timestamps(); // created_at & updated_at
        });

        // Tabel pendukung bawaan Laravel untuk reset password & session
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
