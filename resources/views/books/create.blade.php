@extends('layouts.app')

@section('title', 'Tambah Buku Baru — Smart Library')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap');
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-opensans { font-family: 'Open Sans', sans-serif; }
</style>

<div class="font-opensans text-[#0F4C75] p-6 sm:p-8 w-full max-w-4xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('books.index') }}" class="inline-flex items-center text-sm font-bold text-[#0F4C75] hover:text-[#3282B8] transition-colors group">
            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Buku
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] p-6 md:p-10">

        <div class="mb-8 border-b border-[#BBE1FA] pb-5">
            <h2 class="font-montserrat text-2xl font-bold text-[#1B262C]">Tambah Buku Baru</h2>
            <p class="text-sm text-[#0F4C75] opacity-80 mt-1">Masukkan data metadata dan fisik buku ke dalam sistem perpustakaan.</p>
        </div>

        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <div class="col-span-1 md:col-span-2">
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">Judul Buku <span class="text-[#D32F2F]">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] focus:bg-white focus:border-[#3282B8] focus:ring focus:ring-[#3282B8]/20 text-[#0F4C75] font-semibold text-sm transition-colors outline-none placeholder-[#0F4C75]/40"
                           placeholder="Contoh: Pemrograman Web dengan Laravel">
                    @error('judul') <span class="text-[#D32F2F] text-xs font-bold mt-1.5 flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">Penulis <span class="text-[#D32F2F]">*</span></label>
                    <input type="text" name="penulis" value="{{ old('penulis') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] focus:bg-white focus:border-[#3282B8] focus:ring focus:ring-[#3282B8]/20 text-[#0F4C75] font-semibold text-sm transition-colors outline-none placeholder-[#0F4C75]/40"
                           placeholder="Nama Penulis">
                    @error('penulis') <span class="text-[#D32F2F] text-xs font-bold mt-1.5 flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">Nomor ISBN</label>
                    <input type="text" name="ISBN" value="{{ old('ISBN') }}"
                           class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] focus:bg-white focus:border-[#3282B8] focus:ring focus:ring-[#3282B8]/20 text-[#0F4C75] font-semibold text-sm transition-colors outline-none placeholder-[#0F4C75]/40"
                           placeholder="Format: 978-x-xxxx-xxxx-x">
                    @error('ISBN') <span class="text-[#D32F2F] text-xs font-bold mt-1.5 flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">Kategori <span class="text-[#D32F2F]">*</span></label>
                    <input type="text" name="kategori" value="{{ old('kategori') }}" required
                           class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] focus:bg-white focus:border-[#3282B8] focus:ring focus:ring-[#3282B8]/20 text-[#0F4C75] font-semibold text-sm transition-colors outline-none placeholder-[#0F4C75]/40"
                           placeholder="Contoh: Teknologi, Fiksi...">
                    @error('kategori') <span class="text-[#D32F2F] text-xs font-bold mt-1.5 flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">Cover Buku</label>
                    <div class="flex items-center w-full">
                        <input type="file" name="cover" accept="image/*"
                               class="w-full text-sm font-semibold text-[#0F4C75] file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-montserrat file:font-bold file:bg-[#BBE1FA] file:text-[#0F4C75] hover:file:bg-[#3282B8] hover:file:text-white file:transition-colors file:cursor-pointer cursor-pointer bg-[#F4F9FD] border border-[#BBE1FA] rounded-xl pr-3">
                    </div>
                    <p class="text-[10px] font-bold text-[#0F4C75] opacity-60 mt-2 uppercase tracking-wide">Format: JPG, PNG, WEBP (Max 2MB)</p>
                    @error('cover') <span class="text-[#D32F2F] text-xs font-bold mt-1.5 flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</span> @enderror
                </div>

                <div class="col-span-1 md:col-span-2 mt-2">
                    <label class="block font-montserrat text-xs font-bold text-[#1B262C] uppercase tracking-wider mb-2">Ringkasan / Deskripsi</label>
                    <textarea name="deskripsi" rows="5"
                              class="w-full px-4 py-3 rounded-xl border border-[#BBE1FA] bg-[#F4F9FD] focus:bg-white focus:border-[#3282B8] focus:ring focus:ring-[#3282B8]/20 text-[#0F4C75] font-semibold text-sm transition-colors outline-none placeholder-[#0F4C75]/40"
                              placeholder="Tuliskan sinopsis atau deskripsi singkat mengenai isi buku di sini...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <span class="text-[#D32F2F] text-xs font-bold mt-1.5 flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-10 pt-6 border-t border-[#BBE1FA]">
                <a href="{{ route('books.index') }}" class="py-2.5 px-6 bg-[#F4F9FD] text-[#0F4C75] font-montserrat text-xs font-bold uppercase tracking-wider rounded-lg border border-[#BBE1FA] hover:bg-[#BBE1FA]/50 transition-colors text-center">
                    Batal
                </a>
                <button type="submit" class="py-2.5 px-8 bg-[#1B262C] hover:bg-[#3282B8] text-white font-montserrat text-xs font-bold uppercase tracking-wider rounded-lg transition-colors shadow-[0_4px_10px_rgba(27,38,44,0.2)] flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Buku
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
