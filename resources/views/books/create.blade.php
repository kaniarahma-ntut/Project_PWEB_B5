@extends('layouts.app')

@section('title', 'Tambah Buku Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('books.index') }}" class="text-[#0F4C75] hover:text-[#3282B8] font-semibold flex items-center gap-2">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg border border-[#BBE1FA] shadow-sm p-6 md:p-8">
        <h2 class="text-2xl font-bold text-[#1B262C] mb-6 border-b border-[#BBE1FA] pb-3">Tambah Buku Baru</h2>

        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-semibold text-[#1B262C] mb-2">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required class="w-full rounded border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75]">
                    @error('judul') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#1B262C] mb-2">Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="penulis" value="{{ old('penulis') }}" required class="w-full rounded border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75]">
                    @error('penulis') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#1B262C] mb-2">ISBN</label>
                    <input type="text" name="ISBN" value="{{ old('ISBN') }}" class="w-full rounded border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75]">
                    @error('ISBN') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#1B262C] mb-2">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="kategori" value="{{ old('kategori') }}" required class="w-full rounded border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75]">
                    @error('kategori') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#1B262C] mb-2">Cover Buku</label>
                    <input type="file" name="cover" accept="image/*" class="w-full text-sm text-[#0F4C75] file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-[#BBE1FA] file:text-[#0F4C75] hover:file:bg-[#3282B8] hover:file:text-white transition">
                    @error('cover') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-semibold text-[#1B262C] mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="5" class="w-full rounded border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75]">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-[#BBE1FA]">
                <a href="{{ route('books.index') }}" class="py-2 px-4 bg-gray-100 text-[#1B262C] font-semibold rounded hover:bg-gray-200 transition">Batal</a>
                <button type="submit" class="py-2 px-6 bg-[#3282B8] hover:bg-[#1B262C] text-white font-bold rounded transition">Simpan Buku</button>
            </div>
        </form>
    </div>
</div>
@endsection
