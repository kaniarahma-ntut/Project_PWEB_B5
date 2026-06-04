@extends('layouts.app')

@section('title', $book->judul)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('books.index') }}" class="text-[#0F4C75] hover:text-[#3282B8] font-semibold flex items-center gap-2">
        &larr; Kembali ke Katalog
    </a>

    @if(auth()->user()->isAdmin() || auth()->user()->isPustakawan())
        <div class="flex gap-2">
            @if($book->trashed())
                @if(auth()->user()->isAdmin())
                    <form action="{{ route('books.restore', $book->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded text-sm font-semibold transition" onclick="return confirm('Pulihkan buku ini?')">Restore Buku</button>
                    </form>
                @endif
            @else
                <a href="{{ route('books.edit', $book->id) }}" class="bg-[#3282B8] hover:bg-[#1B262C] text-white py-2 px-4 rounded text-sm font-semibold transition">Edit Data</a>

                <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm font-semibold transition" onclick="return confirm('Yakin ingin menonaktifkan buku ini?')">Nonaktifkan</button>
                </form>
            @endif
        </div>
    @endif
</div>

<div class="bg-white rounded-lg border border-[#BBE1FA] shadow-sm overflow-hidden flex flex-col md:flex-row mb-8 relative">
    @if($book->trashed())
        <div class="absolute inset-0 bg-red-500/10 z-10 pointer-events-none"></div>
    @endif

    <div class="w-full md:w-1/3 lg:w-1/4 bg-[#F8FAFC] flex items-center justify-center p-6 border-b md:border-b-0 md:border-r border-[#BBE1FA]">
        @if($book->cover)
            <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover" class="max-w-full h-auto rounded shadow-sm">
        @else
            <div class="w-full h-64 bg-[#BBE1FA]/50 rounded flex items-center justify-center text-[#0F4C75] font-bold">Tidak ada Cover</div>
        @endif
    </div>

    <div class="w-full md:w-2/3 lg:w-3/4 p-6 lg:p-8">
        <div class="inline-block bg-[#BBE1FA] text-[#1B262C] font-bold px-3 py-1 rounded text-xs mb-3">
            {{ $book->kategori }}
        </div>

        <h1 class="text-3xl font-bold text-[#1B262C] mb-2">{{ $book->judul }}</h1>
        <p class="text-lg text-[#0F4C75] font-semibold mb-6">Oleh: {{ $book->penulis }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm">
            <div class="bg-[#F8FAFC] p-3 rounded border border-[#BBE1FA]">
                <p class="text-[#0F4C75] font-semibold">ISBN</p>
                <p class="text-[#1B262C] font-bold">{{ $book->ISBN ?? '-' }}</p>
            </div>
            <div class="bg-[#F8FAFC] p-3 rounded border border-[#BBE1FA]">
                <p class="text-[#0F4C75] font-semibold">Stok Tersedia</p>
                <p class="text-[#1B262C] font-bold">{{ $book->jumlah_tersedia ?? 0 }} / {{ $book->total_eksemplar ?? 0 }} Eksemplar</p>
            </div>
        </div>

        <div>
            <h3 class="text-xl font-bold text-[#1B262C] mb-2 border-b border-[#BBE1FA] pb-2">Deskripsi Buku</h3>
            <div class="text-[#0F4C75] leading-relaxed">
                {!! nl2br(e($book->deskripsi)) !!}
            </div>
        </div>
    </div>
</div>

@endsection
