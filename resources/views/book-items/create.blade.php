@extends('layouts.app')

@section('title', 'Tambah Eksemplar — ' . $book->judul)

@section('content')
<div class="mb-6">
    <a href="{{ route('books.show', $book->id) }}" class="text-[#0F4C75] hover:text-[#3282B8] font-semibold flex items-center gap-2 transition">
        &larr; Kembali ke Detail Buku
    </a>
</div>

<div class="max-w-3xl mx-auto bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] overflow-hidden">
    <div class="bg-[#F4F9FD]/50 p-6 border-b border-[#BBE1FA]">
        <h2 class="text-2xl font-montserrat font-bold text-[#1B262C]">Tambah Eksemplar Baru</h2>
        <p class="text-sm text-[#0F4C75] mt-1">Buku: <span class="font-bold">{{ $book->judul }}</span></p>
    </div>

    <form action="{{ route('book-items.store', $book->id) }}" method="POST" class="p-6 md:p-8">
        @csrf

        {{-- Kode Buku --}}
        <div class="mb-6">
            <label for="kode_buku" class="block text-sm font-semibold text-[#0F4C75] mb-2">Kode Buku / Eksemplar <span class="text-red-500">*</span></label>
            <input type="text" name="kode_buku" id="kode_buku" value="{{ old('kode_buku') }}" required placeholder="Contoh: BUKU-001-A"
                   class="w-full px-4 py-2.5 rounded-lg border @error('kode_buku') border-red-500 @else border-[#BBE1FA] @enderror focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75] bg-[#F4F9FD] focus:bg-white outline-none transition-colors">
            @error('kode_buku')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-[#0F4C75]/60 mt-1">Gunakan kode unik untuk membedakan fisik buku satu dengan yang lainnya.</p>
        </div>

        {{-- Kode QR --}}
        <div class="mb-6">
            <label for="kode_qr" class="block text-sm font-semibold text-[#0F4C75] mb-2">Kode QR (Opsional)</label>
            <input type="text" name="kode_qr" id="kode_qr" value="{{ old('kode_qr') }}" placeholder="Scan atau ketik kode QR fisik"
                   class="w-full px-4 py-2.5 rounded-lg border @error('kode_qr') border-red-500 @else border-[#BBE1FA] @enderror focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75] bg-[#F4F9FD] focus:bg-white outline-none transition-colors">
            @error('kode_qr')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Status Ketersediaan --}}
        <div class="mb-8">
            <label for="status_ketersediaan" class="block text-sm font-semibold text-[#0F4C75] mb-2">Status Ketersediaan <span class="text-red-500">*</span></label>
            <select name="status_ketersediaan" id="status_ketersediaan" required
                    class="w-full px-4 py-2.5 rounded-lg border @error('status_ketersediaan') border-red-500 @else border-[#BBE1FA] @enderror focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75] bg-[#F4F9FD] focus:bg-white outline-none transition-colors cursor-pointer">
                <option value="Tersedia" {{ old('status_ketersediaan') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="Dipinjam" {{ old('status_ketersediaan') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="Rusak" {{ old('status_ketersediaan') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                <option value="Hilang" {{ old('status_ketersediaan') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
            </select>
            @error('status_ketersediaan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-[#BBE1FA]">
            <a href="{{ route('books.show', $book->id) }}" class="px-6 py-2.5 text-[#0F4C75] font-semibold hover:bg-[#F4F9FD] rounded-lg transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#1B262C] hover:bg-[#3282B8] text-white font-montserrat font-bold rounded-lg shadow-md transition-colors">
                Simpan Eksemplar
            </button>
        </div>
    </form>
</div>
@endsection
