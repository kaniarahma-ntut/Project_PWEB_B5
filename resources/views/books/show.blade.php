@extends('layouts.app')

@section('title', $book->judul)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('books.index') }}" class="text-[#0F4C75] hover:text-[#3282B8] font-semibold flex items-center gap-2">
        &larr; Kembali ke Katalog
    </a>

    <div class="flex gap-2">
        {{-- Wishlist Button (Anggota Only) --}}
        @if(auth()->user()->isAnggota())
            <form action="{{ route('wishlists.toggle') }}" method="POST">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <button type="submit"
                        class="flex items-center gap-2 {{ $inWishlist ? 'bg-red-500 hover:bg-red-600' : 'bg-[#3282B8] hover:bg-[#1B262C]' }} text-white py-2 px-4 rounded text-sm font-semibold transition">
                    <svg class="w-5 h-5" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    {{ $inWishlist ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}
                </button>
            </form>
        @endif

        @if(auth()->user()->isAdmin() || auth()->user()->isPustakawan())
            @if($book->trashed())
                @if(auth()->user()->isAdmin())
                    <form action="{{ route('books.restore', $book->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded text-sm font-semibold transition"
                                onclick="return confirm('Pulihkan buku ini?')">
                            Restore Buku
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('books.edit', $book->id) }}"
                   class="bg-[#3282B8] hover:bg-[#1B262C] text-white py-2 px-4 rounded text-sm font-semibold transition">
                    Edit Data
                </a>

                <form action="{{ route('books.destroy', $book->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded text-sm font-semibold transition"
                            onclick="return confirm('Yakin ingin menonaktifkan buku ini?')">
                        Nonaktifkan
                    </button>
                </form>
            @endif
        @endif
    </div>
</div>


<div class="bg-white rounded-lg border border-[#BBE1FA] shadow-sm overflow-hidden flex flex-col md:flex-row mb-8 relative">
    @if($book->trashed())
        <div class="absolute inset-0 bg-red-500/10 z-10 pointer-events-none"></div>
    @endif

    <div class="w-full md:w-1/3 lg:w-1/4 bg-[#F8FAFC] flex items-center justify-center p-6 border-b md:border-b-0 md:border-r border-[#BBE1FA]">
        @if($book->cover)
            <img src="{{ Storage::url($book->cover) }}"
                 alt="Cover {{ $book->judul }}"
                 class="max-w-full h-auto rounded shadow-sm">
        @else
            <div class="w-full h-64 bg-[#BBE1FA]/50 rounded flex items-center justify-center text-[#0F4C75] font-bold">
                Tidak ada Cover
            </div>
        @endif
    </div>

    <div class="w-full md:w-2/3 lg:w-3/4 p-6 lg:p-8">
        <div class="inline-block bg-[#BBE1FA] text-[#1B262C] font-bold px-3 py-1 rounded text-xs mb-3">
            {{ $book->kategori }}
        </div>

        <h1 class="text-3xl font-bold text-[#1B262C] mb-2">
            {{ $book->judul }}
        </h1>

        <p class="text-lg text-[#0F4C75] font-semibold mb-6">
            Oleh: {{ $book->penulis }}
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm">
            <div class="bg-[#F8FAFC] p-3 rounded border border-[#BBE1FA]">
                <p class="text-[#0F4C75] font-semibold">ISBN</p>
                <p class="text-[#1B262C] font-bold">
                    {{ $book->ISBN ?? '-' }}
                </p>
            </div>

            <div class="bg-[#F8FAFC] p-3 rounded border border-[#BBE1FA]">
                <p class="text-[#0F4C75] font-semibold">Stok Tersedia</p>
                <p class="text-[#1B262C] font-bold">
                    {{ $book->jumlah_tersedia ?? 0 }} / {{ $book->total_eksemplar ?? 0 }} Eksemplar
                </p>
            </div>
        </div>

        <div>
            <h3 class="text-xl font-bold text-[#1B262C] mb-2 border-b border-[#BBE1FA] pb-2">
                Deskripsi Buku
            </h3>

            <div class="text-[#0F4C75] leading-relaxed">
                {!! nl2br(e($book->deskripsi)) !!}
            </div>
        </div>
    </div>
</div>

{{-- DAFTAR EKSEMPLAR --}}
<div class="bg-white rounded-2xl border border-[#BBE1FA] shadow-[0_2px_16px_rgba(27,38,44,0.04)] overflow-hidden">
    <div class="p-6 border-b border-[#BBE1FA] flex justify-between items-center bg-[#F4F9FD]/50">
        <h3 class="text-lg font-montserrat font-bold text-[#1B262C]">
            Daftar Eksemplar
        </h3>

        @if(auth()->user()->isAdmin() || auth()->user()->isPustakawan())
            <a href="{{ route('book-items.create', $book->id) }}"
               class="bg-[#1B262C] hover:bg-[#3282B8] text-white text-xs font-bold py-2 px-4 rounded-lg transition">
                + Tambah Eksemplar
            </a>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-[#0F4C75] text-[10px] uppercase font-bold tracking-wider">
                    <th class="px-6 py-4">Kode Buku</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#BBE1FA]">
                @forelse($book->bookItems as $item)
                    <tr class="hover:bg-[#F4F9FD] transition-colors">
                        <td class="px-6 py-4 font-bold text-[#0F4C75]">
                            {{ $item->kode_buku ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @if($item->status_ketersediaan === 'Tersedia')
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-green-100 text-green-700">
                                    Tersedia
                                </span>
                            @elseif($item->status_ketersediaan === 'Dipinjam')
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-yellow-100 text-yellow-700">
                                    Dipinjam
                                </span>
                            @elseif($item->status_ketersediaan === 'Rusak')
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-red-100 text-red-700">
                                    Rusak
                                </span>
                            @elseif($item->status_ketersediaan === 'Hilang')
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-gray-100 text-gray-700">
                                    Hilang
                                </span>
                            @else
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase bg-gray-100 text-gray-700">
                                    {{ $item->status_ketersediaan ?? '-' }}
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end items-center gap-2">

                                <a href="{{ route('book-items.show', $item->id) }}"
                                   class="text-[#3282B8] hover:text-[#1B262C] text-xs font-bold">
                                    Detail
                                </a>

                                @if(auth()->user()->isAdmin() || auth()->user()->isPustakawan())
                                    <a href="{{ route('book-items.edit', $item->id) }}"
                                       class="text-[#0F4C75] hover:text-[#1B262C] text-xs font-bold">
                                        Edit
                                    </a>
                                @endif

                                @if(auth()->user()->isAnggota() && $item->status_ketersediaan === 'Tersedia')
                                    <form action="{{ route('peminjamans.store') }}"
                                          method="POST"
                                          onsubmit="return confirm('Pinjam eksemplar buku ini?')">
                                        @csrf

                                        <input type="hidden" name="book_item_id" value="{{ $item->id }}">

                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-lg bg-[#3282B8] hover:bg-[#1B262C] text-white text-[10px] font-bold uppercase tracking-wider transition">
                                            Pinjam
                                        </button>
                                    </form>
                                @elseif(auth()->user()->isAnggota() && $item->status_ketersediaan !== 'Tersedia')
                                    <span class="text-[10px] font-bold text-[#0F4C75]/50 uppercase tracking-wider">
                                        Tidak tersedia
                                    </span>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-[#0F4C75] opacity-60 italic">
                            Belum ada eksemplar terdaftar untuk buku ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
