@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-[#1B262C] mb-2">Katalog Buku</h1>
        <p class="text-[#0F4C75]">Jelajahi koleksi buku di Smart Library.</p>
    </div>

    @if(auth()->user()->isAdmin() || auth()->user()->isPustakawan())
        <a href="{{ route('books.create') }}" class="bg-[#3282B8] hover:bg-[#1B262C] text-white font-semibold py-2 px-4 rounded transition duration-200">
            + Tambah Buku
        </a>
    @endif
</div>

<form action="{{ route('books.index') }}" method="GET" class="mb-8 bg-[#F8FAFC] p-4 rounded-lg border border-[#BBE1FA] shadow-sm flex flex-col md:flex-row gap-4">
    <div class="flex-1">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, penulis, atau ISBN..."
               class="w-full rounded border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75]">
    </div>

    <div class="w-full md:w-48">
        <select name="kategori" class="w-full rounded border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] focus:ring-opacity-50 text-[#0F4C75]">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $kat)
                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
            @endforeach
        </select>
    </div>

    @if(auth()->user()->isAdmin() || auth()->user()->isPustakawan())
        <div class="w-full md:w-48">
            <select name="tampilkan" class="w-full rounded border-[#BBE1FA] focus:border-[#3282B8] focus:ring focus:ring-[#BBE1FA] text-[#0F4C75]">
                <option value="aktif" {{ request('tampilkan') == 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
                <option value="nonaktif" {{ request('tampilkan') == 'nonaktif' ? 'selected' : '' }}>Hanya Nonaktif (Sampah)</option>
                <option value="semua" {{ request('tampilkan') == 'semua' ? 'selected' : '' }}>Semua Buku</option>
            </select>
        </div>
    @endif

    <button type="submit" class="bg-[#1B262C] hover:bg-[#0F4C75] text-white font-semibold py-2 px-4 rounded transition">
        Filter
    </button>
</form>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($books as $book)
        <div class="bg-white rounded-lg border border-[#BBE1FA] shadow-sm hover:shadow-md transition overflow-hidden flex flex-col {{ $book->trashed() ? 'opacity-70' : '' }}">
            <div class="h-64 bg-[#BBE1FA]/30 flex items-center justify-center overflow-hidden relative">
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->judul }}" class="w-full h-full object-cover">
                @else
                    <span class="text-[#0F4C75] font-semibold">No Cover</span>
                @endif

                @if($book->trashed())
                    <div class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded font-bold">Nonaktif</div>
                @endif
            </div>

            <div class="p-4 flex-1 flex flex-col">
                <span class="text-xs font-bold text-[#3282B8] uppercase mb-1">{{ $book->kategori }}</span>
                <h2 class="text-lg font-bold text-[#1B262C] mb-1 line-clamp-2">{{ $book->judul }}</h2>
                <p class="text-sm text-[#0F4C75] mb-4">{{ $book->penulis }}</p>

                <div class="mt-auto pt-4 border-t border-[#BBE1FA]/50 flex justify-between items-center">
                    <span class="text-sm font-semibold text-[#0F4C75]">
                        Stok: {{ $book->jumlah_tersedia ?? 0 }}
                    </span>
                    <a href="{{ route('books.show', $book->id) }}" class="text-[#3282B8] hover:text-[#1B262C] font-semibold text-sm transition">
                        Lihat Detail &rarr;
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full py-12 text-center text-[#0F4C75] bg-[#F8FAFC] border border-[#BBE1FA] rounded-lg">
            <p class="font-semibold text-lg">Buku tidak ditemukan.</p>
            <p class="text-sm mt-1">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $books->links() }}
</div>
@endsection
