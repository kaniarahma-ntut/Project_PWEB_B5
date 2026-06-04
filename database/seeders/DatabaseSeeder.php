<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Book;
use App\Models\BookItem;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Membuat Akun Pustakawan (Admin)
        User::create([
            'nama_lengkap' => 'Admin Pustakawan', // Disesuaikan dengan migration: nama_lengkap
            'email' => 'pustakawan@library.com',
            'password' => Hash::make('password123'),
            'role' => 'pustakawan',
        ]);

        // 2. Membuat Akun Anggota
        User::create([
            'nama_lengkap' => 'Kania Rahma Meneling',
            'email' => 'kania@fasilkom.unej.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'anggota',
        ]);

        User::create([
            'nama_lengkap' => 'Reza Azaria Ardhani',
            'email' => 'reza@fasilkom.unej.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'anggota',
        ]);

        User::create([
            'nama_lengkap' => 'Muhammad Raffy Putra Nugraha',
            'email' => 'raffy@fasilkom.unej.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'anggota',
        ]);

        // 3. Menambahkan Koleksi Buku
        $book1 = Book::create([
            'judul' => 'Panduan Lengkap Google Cloud Platform (JuaraGCP)', // title -> judul
            'penulis' => 'Tech Explorer', // author -> penulis
            'kategori' => 'Teknologi',
            // Kolom publisher & published_year dihapus karena tidak ada di migration books
        ]);

        $book2 = Book::create([
            'judul' => 'Implementasi Smart City dengan IoT',
            'penulis' => 'Tim Fasilkom',
            'kategori' => 'Internet of Things',
        ]);

        // 4. Menambahkan Stok Buku Fisik (Book Items)
        BookItem::create([
            'book_id' => $book1->id,
            'kode_buku' => 'GCP-001',
            'status_ketersediaan' => 'Tersedia', // Disesuaikan dengan enum status_ketersediaan
        ]);

        BookItem::create([
            'book_id' => $book1->id,
            'kode_buku' => 'GCP-002',
            'status_ketersediaan' => 'Dipinjam', // Huruf awal kapital sesuai enum di migration
        ]);

        BookItem::create([
            'book_id' => $book2->id,
            'kode_buku' => 'IOT-001',
            'status_ketersediaan' => 'Tersedia',
        ]);
    }
}
