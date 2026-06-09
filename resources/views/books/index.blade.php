@extends('layouts.app')

@section('title', 'Daftar Buku — Smart Library')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Open+Sans:wght@400;600;700&display=swap');
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-opensans { font-family: 'Open Sans', sans-serif; }
</style>

<div class="font-opensans text-[#0F4C75] p-6 sm:p-8 w-full">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 border-b border-[#BBE1FA] pb-6">
        <div class="mb-4 md:mb-0">
            <h1 class="font-montserrat text-2xl font-bold text-[#1B262C]">Daftar Buku</h1>
            <p class="text-sm font-opensans text-[#0F4C75] opacity-80 mt-1">Eksplorasi dan temukan koleksi buku di Smart Library.</p>
        </div>

        @if(auth()->user()->isAdmin() || auth()->user()->isPustakawan())
            <a href="{{ route('books.create') }}" class="inline-flex items-center px-4 py-2 bg-[#3282B8] hover:bg-[#1B262C] text-white font-montserrat text-xs font-bold rounded-lg transition-colors shadow-[0_4px_10px_rgba(50,130,184,0.2)]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Buku
            </a>
        @endif
    </div>

    <form action="{{ route('books.index') }}" method="GET" class="mb-8 bg-white p-5 rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] flex flex-col md:flex-row flex-wrap items-center w-full">

        <div class="flex-1 w-full relative mb-4 md:mb-0 md:mr-4">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-[#0F4C75] opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, penulis, atau ISBN..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75] font-opensans font-semibold text-sm transition-colors outline-none bg-[#F4F9FD] focus:bg-white">
        </div>

        <div class="w-full md:w-56 mb-4 md:mb-0 md:mr-4">
            <select name="kategori" class="w-full px-4 py-2.5 rounded-lg border border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75] font-opensans font-semibold text-sm transition-colors cursor-pointer bg-[#F4F9FD] focus:bg-white outline-none">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>

        @if(auth()->user()->isAdmin() || auth()->user()->isPustakawan())
            <div class="w-full md:w-48 mb-4 md:mb-0 md:mr-4">
                <select name="tampilkan" class="w-full px-4 py-2.5 rounded-lg border border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] text-[#0F4C75] font-opensans font-semibold text-sm transition-colors cursor-pointer bg-[#F4F9FD] focus:bg-white outline-none">
                    <option value="aktif" {{ request('tampilkan') == 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
                    <option value="nonaktif" {{ request('tampilkan') == 'nonaktif' ? 'selected' : '' }}>Hanya Nonaktif</option>
                    <option value="semua" {{ request('tampilkan') == 'semua' ? 'selected' : '' }}>Semua Buku</option>
                </select>
            </div>
        @endif

        <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-[#1B262C] hover:bg-[#3282B8] text-white font-montserrat text-xs font-bold rounded-lg transition-colors shadow-md">
            Cari & Filter
        </button>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($books as $book)
            <div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] hover:shadow-[0_8px_40px_rgba(27,38,44,0.08)] hover:border-[#3282B8] transition-all overflow-hidden flex flex-col group {{ $book->trashed() ? 'opacity-75 grayscale-[20%]' : '' }}">

                <div class="h-64 bg-[#F4F9FD] flex items-center justify-center overflow-hidden relative border-b border-[#BBE1FA]/50">
                    @if($book->cover)
                        <img src="{{ Storage::url($book->cover) }}" alt="Cover {{ $book->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="flex flex-col items-center justify-center text-[#0F4C75] opacity-40">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477-4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <span class="font-montserrat font-bold text-[10px] uppercase tracking-widest">No Cover</span>
                        </div>
                    @endif

                    @if($book->trashed())
                        <div class="absolute top-3 right-3 bg-[#D32F2F] text-white text-[10px] px-2.5 py-1 rounded-md font-montserrat font-bold uppercase tracking-wider shadow-sm">
                            Nonaktif
                        </div>
                    @endif
                </div>

                <div class="p-5 flex-1 flex flex-col">
                    <div class="mb-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-[#3282B8]/10 text-[#3282B8] text-[10px] font-montserrat font-bold uppercase tracking-wider">
                            {{ $book->kategori }}
                        </span>
                    </div>
                    <h2 class="text-base font-montserrat font-bold text-[#1B262C] mb-1 line-clamp-2 leading-snug group-hover:text-[#3282B8] transition-colors" title="{{ $book->judul }}">
                        {{ $book->judul }}
                    </h2>
                    <p class="text-xs font-opensans font-semibold text-[#0F4C75] opacity-70 mb-4 line-clamp-1">
                        {{ $book->penulis }}
                    </p>

                    <div class="mt-auto pt-4 border-t border-[#BBE1FA]/50 flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-montserrat font-bold text-[#0F4C75] opacity-60 uppercase tracking-wider mb-0.5">Stok Tersedia</span>
                            <span class="text-sm font-opensans font-bold {{ ($book->jumlah_tersedia ?? 0) > 0 ? 'text-[#1B262C]' : 'text-[#D32F2F]' }}">
                                {{ $book->jumlah_tersedia ?? 0 }} <span class="text-xs font-semibold opacity-70">Eks</span>
                            </span>
                        </div>
                        <a href="{{ route('books.show', $book->id) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-[#F4F9FD] text-[#3282B8] hover:bg-[#3282B8] hover:text-white transition-colors" title="Lihat Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 flex flex-col items-center justify-center bg-white border border-[#BBE1FA] rounded-2xl shadow-[0_2px_16px_rgba(27,38,44,0.04)]">
                <div class="w-16 h-16 bg-[#F4F9FD] rounded-full flex items-center justify-center text-[#0F4C75] opacity-50 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                    </svg>
                </div>
                <p class="font-montserrat font-bold text-lg text-[#1B262C] mb-1">Buku Tidak Ditemukan</p>
                <p class="font-opensans text-sm font-semibold text-[#0F4C75] opacity-70 text-center max-w-md">Coba gunakan kata kunci lain atau sesuaikan filter kategori untuk menemukan buku yang dicari.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $books->links() }}
    </div>
</div>
@endsection
